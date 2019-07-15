<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Quest as QuestEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterQuest;
use Nextras\Orm\Collection\ICollection;
use Nette\Localization\ITranslator;
use Nette\Application\LinkGenerator;

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
  /** @var ITranslator */
  protected $translator;
  /** @var LinkGenerator */
  protected $linkGenerator;
  
  public function __construct(ORM $orm, \Nette\Security\User $user, Item $itemModel, Pet $petModel, ITranslator $translator, LinkGenerator $linkGenerator) {
    $this->orm = $orm;
    $this->user = $user;
    $this->itemModel = $itemModel;
    $this->petModel = $petModel;
    $this->petModel->user = $user;
    $this->translator = $translator;
    $this->linkGenerator = $linkGenerator;
  }
  
  /**
   * Gets list of quests
   * 
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return QuestEntity[]
   */
  public function listOfQuests(int $npc = 0): array {
    if($npc === 0) {
      $quests = $this->orm->quests->findAll();
    } else {
      $quests = $this->orm->quests->findBy([
        ICollection::OR,
        "npcStart" => $npc,
        "npcEnd" => $npc,
      ]);
    }
    return $quests->fetchPairs("id", null);
  }
  
  /**
   * Gets info about specified quest
   */
  public function view(int $id): ?QuestEntity {
    return $this->orm->quests->getById($id);
  }

  public function getCharacterQuest(int $id): CharacterQuest {
    $row = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $id);
    if(is_null($row)) {
      $row = new CharacterQuest();
      $this->orm->characterQuests->attach($row);
      $row->character = $this->user->id;
      $row->quest = $id;
      $row->progress = CharacterQuest::PROGRESS_OFFERED;
    }
    return $row;
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
    if($quest->neededMoney > 0) {
      /** @var \HeroesofAbenez\Orm\Character $char */
      $char = $this->orm->characters->getById($this->user->id);
      if($quest->neededMoney >= $char->money) {
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
    $record = $this->getCharacterQuest($id);
    if($record->progress === CharacterQuest::PROGRESS_OFFERED || $record->progress === CharacterQuest::PROGRESS_FINISHED) {
      throw new QuestNotStartedException();
    }
    if($quest->npcEnd->id !== $npcId) {
      throw new CannotFinishQuestHereException();
    }
    if(!$this->isCompleted($quest)) {
      throw new QuestNotFinishedException();
    }
    $record->progress = CharacterQuest::PROGRESS_FINISHED;
    if($quest->itemLose) {
      $this->itemModel->loseItem($quest->neededItem->id, $quest->itemAmount);
    }
    $record->character->money -= $quest->neededMoney;
    $record->character->money += $record->rewardMoney;
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
    if($this->user->identity->level < $quest->requiredLevel) {
      return false;
    }
    if(!is_null($quest->requiredClass)) {
      if($this->user->identity->class !== $quest->requiredClass->id) {
        return false;
      }
    }
    if(!is_null($quest->requiredRace)) {
      if($this->user->identity->race !== $quest->requiredRace->id) {
        return false;
      }
    }
    if(!is_null($quest->requiredQuest)) {
      if(!$this->isFinished($quest->requiredQuest->id)) {
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
    $record = $this->getCharacterQuest($id);
    if($record->progress !== CharacterQuest::PROGRESS_OFFERED) {
      throw new QuestAlreadyStartedException();
    }
    if($quest->npcStart->id !== $npcId) {
      throw new CannotAcceptQuestHereException();
    }
    if(!$this->isAvailable($quest)) {
      throw new QuestNotAvailableException();
    }
    $record->progress = CharacterQuest::PROGRESS_STARTED;
    $this->orm->characterQuests->persistAndFlush($record);
  }

  /**
   * @return \stdClass[]
   */
  public function getRequirements(QuestEntity $quest): array {
    $requirements = [];
    if($quest->neededMoney > 0) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementPayMoney", $quest->neededMoney),
        "met" => false
      ];
    }
    if(!is_null($quest->neededItem)) {
      $itemName = $this->translator->translate("items.{$quest->neededItem->id}.name");
      $itemLink = $this->linkGenerator->link("Item:view", ["id" => $quest->neededItem->id]);
      $haveItem = $this->itemModel->haveItem($quest->neededItem->id, $quest->itemAmount);
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementGetItem", $quest->itemAmount, ["item" => "<a href=\"$itemLink\">$itemName</a>"]),
        "met" => $haveItem
      ];
    }
    $npcLink = $this->linkGenerator->link("Npc:view", ["id" => $quest->npcEnd->id]);
    $npcName = $this->translator->translate("npcs.{$quest->npcEnd->id}.name");
    if($quest->npcStart->id != $quest->npcEnd->id) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementTalkToNpc", 0, ["npc" => "<a href=\"$npcLink\">{$npcName}</a>"]),
        "met" => false
      ];
    } else {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementReportBackToNpc", 0, ["npc" => "<a href=\"$npcLink\">{$npcName}</a>"]),
        "met" => false
      ];
    }
    return $requirements;
  }
}
?>