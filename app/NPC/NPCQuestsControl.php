<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Model;
use Nette\Localization\ITranslator;
use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Orm\Quest as QuestEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterQuest;
use HeroesofAbenez\Model\QuestNotFoundException;
use HeroesofAbenez\Model\QuestNotStartedException;
use HeroesofAbenez\Model\QuestAlreadyStartedException;
use HeroesofAbenez\Model\CannotFinishQuestHereException;
use HeroesofAbenez\Model\CannotAcceptQuestHereException;
use HeroesofAbenez\Model\QuestNotFinishedException;
use HeroesofAbenez\Model\QuestNotAvailableException;

/**
 * NPC Quests Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @property-write Npc $npc
 */
final class NPCQuestsControl extends \Nette\Application\UI\Control {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $questModel;
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ITranslator */
  protected $translator;
  /** @var Npc */
  protected $npc;

  public function __construct(Model\Quest $questModel, ORM $orm, \Nette\Security\User $user, ITranslator $translator) {
    parent::__construct();
    $this->questModel = $questModel;
    $this->user = $user;
    $this->orm = $orm;
    $this->translator = $translator;
  }

  public function setNpc(Npc $npc): void {
    $this->npc = $npc;
  }

  /**
   * Gets list of available quests from the npc
   *
   * @return QuestEntity[]
   */
  public function getQuests(): array {
    $return = $this->questModel->listOfQuests($this->npc->id);
    $playerQuests = $this->orm->characterQuests->findByCharacter($this->user->id);
    foreach($return as $key => $quest) {
      foreach($playerQuests as $pquest) {
        if($quest->id === $pquest->quest->id AND $pquest->progress >= CharacterQuest::PROGRESS_FINISHED) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id === $pquest->quest->id AND $pquest->progress < CharacterQuest::PROGRESS_FINISHED) {
          $quest->progress = true;
          continue 2;
        }
      }
      if(!$this->questModel->isAvailable($quest)) {
        unset($return[$key]);
      }
    }
    return $return;
  }

  public function render(): void {
    $this->template->setFile(__DIR__ . "/npcQuests.latte");
    $this->template->id = $this->npc->id;
    $this->template->quests = $this->getQuests();
    $this->template->render();
  }

  /**
   * Accept a quest
   */
  public function handleAccept(int $questId): void {
    try {
      $this->questModel->accept($questId, $this->npc->id);
    } catch(QuestNotFoundException $e) {
      $this->presenter->forward("notfound");
    } catch(QuestAlreadyStartedException $e) {
      /** @var QuestEntity $quest */
      $quest = $this->questModel->view($questId);
      $this->presenter->flashMessage("errors.quest.workingOn");
      $this->presenter->redirect("Npc:quests", $quest->npcStart->id);
    } catch(CannotAcceptQuestHereException $e) {
      $this->presenter->flashMessage("errors.quest.cannotAcceptHere");
      $this->presenter->redirect("Homepage:default");
    } catch(QuestNotAvailableException $e) {
      $this->presenter->flashMessage("errors.quest.questNotAvailable");
      $this->presenter->redirect("Homepage:default");
    }
    $this->presenter->flashMessage("messages.quest.accepted");
    $this->presenter->redirect("Quest:view", $questId);
  }

  /**
   * Finish a quest
   */
  public function handleFinish(int $questId): void {
    try {
      $this->questModel->finish($questId, $this->npc->id);
    } catch(QuestNotFoundException $e) {
      $this->presenter->forward("notfound");
    } catch(QuestNotStartedException $e) {
      /** @var QuestEntity $quest */
      $quest = $this->questModel->view($questId);
      $this->presenter->flashMessage("errors.quest.notWorkingOn");
      $this->presenter->redirect("Npc:quests", $quest->npcStart->id);
    } catch(CannotFinishQuestHereException $e) {
      $this->presenter->flashMessage("errors.quest.cannotFinishHere");
      $this->presenter->redirect("Homepage:default");
    } catch(QuestNotFinishedException $e) {
      $this->presenter->flashMessage("errors.quest.requirementsNotMet");
      $this->presenter->redirect("Homepage:default");
    }
    $this->presenter->flashMessage("messages.quest.finished");
    $this->user->logout();
    /** @var QuestEntity $quest */
    $quest = $this->questModel->view($questId);
    $this->presenter->redirect("Quest:view", $quest->id);
  }
}
?>