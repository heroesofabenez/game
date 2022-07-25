<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\ArenaFightCount;
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

  private ORM $orm;
  private \Nette\Security\User $user;
  private Item $itemModel;
  private Pet $petModel;
  private ITranslator $translator;
  private LinkGenerator $linkGenerator;
  
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
    if($row === null) {
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
    if($row === null) {
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
  public function isCompleted(CharacterQuest $characterQuest): bool {
    if($characterQuest->quest->neededMoney > 0) {
      if($characterQuest->quest->neededMoney >= $characterQuest->character->money) {
        return false;
      }
    }
    if($characterQuest->quest->neededItem !== null) {
      if(!$this->itemModel->haveItem($characterQuest->quest->neededItem->id, $characterQuest->quest->itemAmount)) {
        return false;
      }
    }
    if($characterQuest->quest->neededArenaWins > 0) {
      if($characterQuest->arenaWins < $characterQuest->quest->neededArenaWins) {
        return false;
      }
    }
    if($characterQuest->quest->neededGuildDonation > 0) {
      if($characterQuest->guildDonation < $characterQuest->quest->neededGuildDonation) {
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
    if($quest === null) {
      throw new QuestNotFoundException();
    }
    $record = $this->getCharacterQuest($id);
    if($record->progress === CharacterQuest::PROGRESS_OFFERED || $record->progress === CharacterQuest::PROGRESS_FINISHED) {
      throw new QuestNotStartedException();
    }
    if($quest->npcEnd->id !== $npcId) {
      throw new CannotFinishQuestHereException();
    }
    if(!$this->isCompleted($record)) {
      throw new QuestNotFinishedException();
    }
    $record->progress = CharacterQuest::PROGRESS_FINISHED;
    if($quest->itemLose) {
      $this->itemModel->loseItem(($quest->neededItem !== null) ? $quest->neededItem->id : 0, $quest->itemAmount);
    }
    $record->character->money -= $quest->neededMoney;
    $record->character->money += $record->rewardMoney;
    $record->character->experience += $quest->rewardXp;
    if($quest->rewardItem !== null) {
      $this->itemModel->giveItem($quest->rewardItem->id);
    }
    if($quest->rewardPet !== null) {
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
    if($quest->requiredClass !== null) {
      if($this->user->identity->class !== $quest->requiredClass->id) {
        return false;
      }
    }
    if($quest->requiredRace !== null) {
      if($this->user->identity->race !== $quest->requiredRace->id) {
        return false;
      }
    }
    if($quest->requiredQuest !== null) {
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
    if($quest === null) {
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
    $characterQuest = $this->getCharacterQuest($quest->id);
    if($quest->neededMoney > 0) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementPayMoney", $quest->neededMoney),
        "met" => false,
      ];
    }
    if($quest->neededItem !== null) {
      $itemName = $quest->neededItem->name;
      $itemLink = $this->linkGenerator->link("Item:view", ["id" => $quest->neededItem->id]);
      $haveItem = $this->itemModel->haveItem($quest->neededItem->id, $quest->itemAmount);
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementGetItem", $quest->itemAmount, ["item" => "<a href=\"$itemLink\">$itemName</a>"]),
        "met" => $haveItem,
      ];
    }
    if($quest->neededArenaWins > 0) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementArenaWins", $quest->neededArenaWins),
        "met" => ($characterQuest->arenaWins >= $quest->neededArenaWins),
      ];
    }
    if($quest->neededGuildDonation > 0) {
      $requirements[] = (object) [
        "text" => $this->translator->translate("texts.quest.requirementGuildDonation", $quest->neededGuildDonation),
        "met" => ($characterQuest->guildDonation >= $quest->neededGuildDonation),
      ];
    }
    $npcLink = $this->linkGenerator->link("Npc:view", ["id" => $quest->npcEnd->id]);
    $npcName = $quest->npcEnd->name;
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