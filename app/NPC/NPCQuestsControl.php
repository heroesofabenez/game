<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Model;
use Nette\Localization\ITranslator;
use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Orm\QuestDummy as QuestEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterQuest;

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
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ITranslator */
  protected $translator;
  /** @var Npc */
  protected $npc;
  
  public function __construct(Model\Quest $questModel, Model\Item $itemModel, ORM $orm, \Nette\Security\User $user, ITranslator $translator) {
    parent::__construct();
    $this->questModel = $questModel;
    $this->itemModel = $itemModel;
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
        if($quest->id == $pquest->quest->id AND $pquest->progress >= CharacterQuest::PROGRESS_FINISHED) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id == $pquest->quest->id AND $pquest->progress < CharacterQuest::PROGRESS_FINISHED) {
          $quest->progress = true;
          continue 2;
        }
      }
      if($quest->neededLevel > 0) {
        if($this->user->identity->level < $quest->neededLevel) {
          unset($return[$key]);
        }
      } elseif($quest->neededQuest > 0) {
        if(!$this->questModel->isFinished($quest->id)) {
          unset($return[$key]);
        }
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
    $quest = $this->questModel->view($questId);
    if(is_null($quest)) {
      $this->presenter->forward("notfound");
    }
    $status = $this->questModel->status($questId);
    if($status > 0) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.workingOn"));
      $this->presenter->redirect("Npc:quests", $quest->npcStart);
    }
    if($quest->npcStart != $this->npc->id) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.cannotAcceptHere"));
      $this->presenter->redirect("Homepage:default");
    }
    $record = new CharacterQuest();
    $this->orm->characterQuests->attach($record);
    $record->character = $this->user->id;
    $record->quest = $questId;
    $this->orm->characterQuests->persistAndFlush($record);
    $this->presenter->flashMessage($this->translator->translate("messages.quest.accepted"));
    $this->presenter->redirect("Quest:view", $quest->id);
  }
  
  /**
   * Checks if the player accomplished specified quest's goals
   */
  protected function isCompleted(QuestEntity $quest): bool {
    if($quest->costMoney > 0) {
      /** @var \HeroesofAbenez\Orm\Character $char */
      $char = $this->orm->characters->getById($this->user->id);
      if($quest->costMoney >= $char->money) {
        return false;
      }
    }
    if(!is_null($quest->neededItem)) {
      if(!$this->itemModel->haveItem($quest->neededItem, $quest->itemAmount)) {
        return false;
      }
    }
    return true;
  }
  
  /**
   * Finish a quest
   */
  public function handleFinish(int $questId): void {
    $quest = $this->questModel->view($questId);
    if(is_null($quest)) {
      $this->presenter->forward("notfound");
    }
    $status = $this->questModel->status($questId);
    if($status === CharacterQuest::PROGRESS_OFFERED) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.notWorkingOn"));
      $this->presenter->redirect("Npc:quests", $quest->npcStart);
    }
    if($quest->npcEnd != $this->npc->id) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.cannotFinishHere"));
      $this->presenter->redirect("Homepage:default");
    }
    if(!$this->isCompleted($quest)) {
      $this->presenter->flashMessage($this->translator->translate("errors.quest.requirementsNotMet"));
      $this->presenter->redirect("Homepage:default");
    }
    /** @var CharacterQuest $record */
    $record = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $questId);
    $record->progress = CharacterQuest::PROGRESS_FINISHED;
    if($quest->itemLose) {
      $this->itemModel->loseItem($quest->neededItem, $quest->itemAmount);
    }
    $record->character->money -= $quest->costMoney;
    $record->character->money += $quest->rewardMoney;
    $record->character->experience += $quest->rewardXp;
    if($quest->rewardItem > 0) {
      $this->itemModel->giveItem($quest->rewardItem);
    }
    if($quest->rewardWhiteKarma > 0) {
      $record->character->whiteKarma += $quest->rewardWhiteKarma;
    }
    if($quest->rewardDarkKarma > 0) {
      $record->character->darkKarma += $quest->rewardDarkKarma;
    }
    $this->orm->characterQuests->persistAndFlush($record);
    $this->presenter->flashMessage($this->translator->translate("messages.quest.finished"));
    $this->user->logout();
    $this->presenter->redirect("Quest:view", $quest->id);
  }
}
?>