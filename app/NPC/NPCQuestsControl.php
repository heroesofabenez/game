<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Model,
    Nette\Localization\ITranslator,
    HeroesofAbenez\Orm\Npc,
    HeroesofAbenez\Orm\QuestDummy as QuestEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\CharacterQuest;

/**
 * NPC Quests Control
 *
 * @author Jakub Konečný
 * @property-write Npc $npc
 */
class NPCQuestsControl extends \Nette\Application\UI\Control {
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
  
  function __construct(Model\Quest $questModel, Model\Item $itemModel, ORM $orm, \Nette\Security\User $user, ITranslator $translator) {
    parent::__construct();
    $this->questModel = $questModel;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->orm = $orm;
    $this->translator = $translator;
  }
  
  function setNpc(Npc $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Gets list of available quests from the npc
   *
   * @return QuestEntity[]
   */
  function getQuests(): array {
    $return = $this->questModel->listOfQuests($this->npc->id);
    $playerQuests = $this->orm->characterQuests->findByCharacter($this->user->id);
    foreach($return as $key => $quest) {
      foreach($playerQuests as $pquest) {
        if($quest->id == $pquest->quest->id AND $pquest->progress > 2) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id == $pquest->quest->id AND $pquest->progress <= 2) {
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
  
  function render(): void {
    $this->template->setFile(__DIR__ . "/npcQuests.latte");
    $this->template->id = $this->npc->id;
    $this->template->quests = $this->getQuests();
    $this->template->render();
  }
  
  /**
   * Accept a quest
   */
  function handleAccept(int $questId): void {
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
    $record = new CharacterQuest;
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
    $haveMoney = $haveItem = false;
    if($quest->costMoney > 0) {
      $char = $this->orm->characters->getById($this->user->id);
      if($char->money >= $quest->costMoney) {
        $haveMoney = true;
      }
    } else {
      $haveMoney = true;
    }
    if(is_int($quest->neededItem)) {
      $haveItem = $this->itemModel->haveItem($quest->neededItem, $quest->itemAmount);
    } else {
      $haveItem = true;
    }
    return ($haveMoney AND $haveItem);
  }
  
  /**
   * Finish a quest
   */
  function handleFinish(int $questId): void {
    $quest = $this->questModel->view($questId);
    if(is_null($quest)) {
      $this->presenter->forward("notfound");
    }
    $status = $this->questModel->status($questId);
    if($status === 0) {
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
    $record = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $questId);
    $record->progress = 3;
    if($quest->itemLose) {
      $this->itemModel->loseItem($quest->neededItem, $quest->itemAmount);
    }
    if($quest->costMoney > 0) {
      $record->character->money -= $quest->costMoney;
    } else {
      $record->character->money += $quest->rewardMoney;
    }
    $record->character->experience += $quest->rewardXp;
    if($quest->rewardItem > 0) {
      $this->itemModel->giveItem($quest->rewardItem);
    }
    $this->orm->characterQuests->persistAndFlush($record);
    $this->presenter->flashMessage($this->translator->translate("messages.quest.finished"));
    $this->presenter->redirect("Quest:view", $quest->id);
  }
}
?>