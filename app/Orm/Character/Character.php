<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use HeroesofAbenez\Utils\Karma;
use Nexendrie\Utils\Numbers;

/**
 * Character
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterRace $race {m:1 CharacterRace::$characters}
 * @property string $gender {enum self::GENDER_*}
 * @property CharacterClass $class {m:1 CharacterClass::$characters}
 * @property CharacterSpecialization|null $specialization {m:1 CharacterSpecialization::$characters}
 * @property int $level {default 1}
 * @property int $money {default 0}
 * @property int $experience {default 0}
 * @property float $strength
 * @property float $dexterity
 * @property float $constitution
 * @property float $intelligence
 * @property float $charisma
 * @property string|null $description
 * @property Guild|null $guild {m:1 Guild::$members} {default null}
 * @property GuildRank|null $guildrank {m:1 GuildRank::$characters} {default null}
 * @property int $owner
 * @property QuestStage|null $currentStage {m:1 QuestStage::$characters} {default null}
 * @property int $whiteKarma {default 0}
 * @property int $darkKarma {default 0}
 * @property-read string $predominantKarma {virtual}
 * @property int|null $intro {default 1}
 * @property \DateTimeImmutable|null $joined
 * @property float $statPoints {default 0}
 * @property int $skillPoints {default 0}
 * @property OneHasMany|Request[] $sentRequests {1:m Request::$from}
 * @property OneHasMany|Request[] $receivedRequests {1:m Request::$to}
 * @property OneHasMany|Message[] $sentMessages {1:m Message::$from}
 * @property OneHasMany|Message[] $receivedMessages {1:m Message::$to}
 * @property OneHasMany|Pet[] $pets {1:m Pet::$owner}
 * @property-read Pet|null $activePet {virtual}
 * @property OneHasMany|ArenaFightCount[] $arenaFights {1:m ArenaFightCount::$character}
 * @property OneHasMany|CharacterItem[] $items {1:m CharacterItem::$character}
 * @property OneHasMany|CharacterQuest[] $quests {1:m CharacterQuest::$character}
 * @property OneHasMany|ChatBan[] $chatBans {1:m ChatBan::$character}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$character}
 * @property OneHasMany|CharacterAttackSkill[] $attackSkills {1:m CharacterAttackSkill::$character}
 * @property OneHasMany|CharacterSpecialSkill[] $specialSkills {1:m CharacterSpecialSkill::$character}
 */
final class Character extends \Nextras\Orm\Entity\Entity {
  public const GENDER_MALE = "male";
  public const GENDER_FEMALE = "female";
  
  protected function setterLevel(int $value): int {
    return Numbers::range($value, 1, 999);
  }
  
  protected function getterPredominantKarma(): string {
    return Karma::getPredominant($this->whiteKarma, $this->darkKarma);
  }

  protected function getterActivePet(): ?Pet {
    return $this->pets->get()->getBy(["deployed" => true]);
  }
  
  public function onBeforeInsert(): void {
    $this->joined = new \DateTimeImmutable();
  }
}
?>