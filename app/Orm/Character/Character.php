<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Character
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterRace $race {m:1 CharacterRace::$characters}
 * @property string $gender {enum self::GENDER_*}
 * @property CharacterClass $occupation {m:1 CharacterClass::$characters}
 * @property CharacterSpecialization|NULL $specialization {m:1 CharacterSpecialization::$characters}
 * @property int $level {default 1}
 * @property int $money {default 0}
 * @property int $experience {default 0}
 * @property float $strength
 * @property float $dexterity
 * @property float $constitution
 * @property float $intelligence
 * @property float $charisma
 * @property string|NULL $description
 * @property Guild|NULL $guild {m:1 Guild::$members} {default NULL}
 * @property GuildRank|NULL $guildrank {m:1 GuildRank::$characters} {default NULL}
 * @property int $owner
 * @property QuestStage|NULL $currentStage {m:1 QuestStage::$characters} {default NULL}
 * @property int|NULL $whiteKarma {default 0}
 * @property int|NULL $neutralKarma {default 0}
 * @property int|NULL $darkKarma {default 0}
 * @property int|NULL $intro {default 1}
 * @property \DateTime|NULL $joined
 * @property float $statPoints {default 0}
 * @property int $skillPoints {default 0}
 * @property OneHasMany|Request[] $sentRequests {1:m Request::$from}
 * @property OneHasMany|Request[] $receivedRequests {1:m Request::$to}
 * @property OneHasMany|Message[] $sentMessages {1:m Message::$from}
 * @property OneHasMany|Message[] $receivedMessages {1:m Message::$to}
 * @property OneHasMany|Pet[] $pets {1:m Pet::$owner}
 * @property OneHasMany|ArenaFightCount[] $arenaFights {1:m ArenaFightCount::$character}
 * @property OneHasMany|CharacterItem[] $items {1:m CharacterItem::$character}
 * @property OneHasMany|CharacterEquipment[] $equipment {1:m CharacterEquipment::$character}
 * @property OneHasMany|CharacterQuest[] $quests {1:m CharacterQuest::$character}
 * @property OneHasMany|ChatBan[] $chatBans {1:m ChatBan::$character}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$character}
 * @property OneHasMany|CharacterAttackSkill $attackSkills {1:m CharacterAttackSkill::$character}
 * @property OneHasMany|CharacterSpecialSkill $specialSkills {1:m CharacterSpecialSkill::$character}
 */
class Character extends \Nextras\Orm\Entity\Entity {
  const GENDER_MALE = "male";
  const GENDER_FEMALE = "female";
  
  protected function setterLevel(int $value): int {
    if($value < 1) {
      return 1;
    } elseif($value > 999) {
      return 999;
    } else {
      return $value;
    }
  }
  
  protected function onBeforeInsert() {
    $this->joined = new \DateTime;
  }
}
?>