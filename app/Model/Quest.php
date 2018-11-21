<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Quest as QuestEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterQuest;

/**
 * Quest Model
 * 
 * @author Jakub Konečný
 */
final class Quest {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var Item */
  protected $itemModel;
  /** @var Pet */
  protected $petModel;
  
  public function __construct(ORM $orm,  \Nette\Security\User $user, Item $itemModel, Pet $petModel) {
    $this->orm = $orm;
    $this->user = $user;
    $this->itemModel = $itemModel;
    $this->petModel = $petModel;
    $this->petModel->user = $user;
  }
  
  /**
   * Gets list of quests
   * 
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return QuestEntity[]
   */
  public function listOfQuests(int $npc = 0): array {
    $quests = [];
    $records = $this->orm->quests->findAll();
    foreach($records as $record) {
      $quests[$record->id] = $record;
    }
    if($npc > 0) {
      foreach($quests as $quest) {
        if($quest->npcStart->id !== $npc OR $quest->npcEnd->id !== $npc) {
          unset($quests[$quest->id]);
        }
      }
    }
    return $quests;
  }
  
  /**
   * Gets info about specified quest
   */
  public function view(int $id): ?QuestEntity {
    return $this->orm->quests->getById($id);
  }
  
  /**
   * Get quest's status
   */
  public function status(int $id): int {
    $row = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $id);
    if(is_null($row)) {
      return CharacterQuest::PROGRESS_OFFERED;
    }
    return $row->progress;
  }
  
  /**
   * Checks if the player finished specified quest
   */
  public function isFinished(int $id): bool {
    $status = $this->status($id);
    return ($status >= CharacterQuest::PROGRESS_FINISHED);
  }

  /**
   * Checks if the player accomplished specified quest's goals
   */
  public function isCompleted(QuestEntity $quest): bool {
    if($quest->costMoney > 0) {
      /** @var \HeroesofAbenez\Orm\Character $char */
      $char = $this->orm->characters->getById($this->user->id);
      if($quest->costMoney >= $char->money) {
        return false;
      }
    }
    if(!is_null($quest->neededItem)) {
      if(!$this->itemModel->haveItem($quest->neededItem->id, $quest->itemAmount)) {
        return false;
      }
    }
    return true;
  }

  /**
   * @throws QuestNotFoundException
   * @throws QuestNotStartedException
   * @throws CannotFinishQuestHereException
   * @throws QuestNotFinishedException
   */
  public function finish(int $id, int $npcId): void {
    $quest = $this->view($id);
    if(is_null($quest)) {
      throw new QuestNotFoundException();
    }
    $status = $this->status($id);
    if($status === CharacterQuest::PROGRESS_OFFERED OR $status === CharacterQuest::PROGRESS_FINISHED) {
      throw new QuestNotStartedException();
    }
    if($quest->npcEnd->id !== $npcId) {
      throw new CannotFinishQuestHereException();
    }
    if(!$this->isCompleted($quest)) {
      throw new QuestNotFinishedException();
    }
    /** @var CharacterQuest $record */
    $record = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $id);
    $record->progress = CharacterQuest::PROGRESS_FINISHED;
    if($quest->itemLose) {
      $this->itemModel->loseItem($quest->neededItem->id, $quest->itemAmount);
    }
    $record->character->money -= $quest->costMoney;
    $record->character->money += $quest->rewardMoney;
    $record->character->experience += $quest->rewardXp;
    if(!is_null($quest->rewardItem)) {
      $this->itemModel->giveItem($quest->rewardItem->id);
    }
    if(!is_null($quest->rewardPet)) {
      $this->petModel->givePet($quest->rewardPet->id);
    }
    $record->character->whiteKarma += $quest->rewardWhiteKarma;
    $record->character->darkKarma += $quest->rewardDarkKarma;
    $this->orm->characterQuests->persistAndFlush($record);
  }

  public function isAvailable(QuestEntity $quest): bool {
    if($this->user->identity->level < $quest->neededLevel) {
      return false;
    } elseif(!is_null($quest->neededQuest)) {
      if(!$this->isFinished($quest->neededQuest->id)) {
        return false;
      }
    }
    return true;
  }

  /**
   * @throws QuestNotFoundException
   * @throws QuestAlreadyStartedException
   * @throws CannotAcceptQuestHereException
   * @throws QuestNotAvailableException
   */
  public function accept(int $id, int $npcId): void {
    $quest = $this->view($id);
    if(is_null($quest)) {
      throw new QuestNotFoundException();
    }
    $status = $this->status($id);
    if($status !== CharacterQuest::PROGRESS_OFFERED) {
      throw new QuestAlreadyStartedException();
    }
    if($quest->npcStart->id !== $npcId) {
      throw new CannotAcceptQuestHereException();
    }
    if(!$this->isAvailable($quest)) {
      throw new QuestNotAvailableException();
    }
    $record = new CharacterQuest();
    $this->orm->characterQuests->attach($record);
    $record->character = $this->user->id;
    $record->quest = $id;
    $this->orm->characterQuests->persistAndFlush($record);
  }
}
?>