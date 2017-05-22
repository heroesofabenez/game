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
 * @property Guild $guild {m:1 Guild::$members} {default 0}
 * @property GuildRank|NULL $guildrank {m:1 GuildRank::$characters} {default NULL}
 * @property int $owner
 * @property int|NULL $currentStage {default NULL}
 * @property int|NULL $whiteKarma {default 0}
 * @property int|NULL $neutralKarma {default 0}
 * @property int|NULL $darkKarma {default 0}
 * @property int|NULL $intro {default 1}
 * @property \DateTime|NULL $joined
 * @property float|NULL $statPoints {default 0}
 * @property int|NULL $skillPoints {default 0}
 * @property OneHasMany|Request[] $sentRequests {1:m Request::$from}
 * @property OneHasMany|Request[] $receivedRequests {1:m Request::$to}
 */
class Character extends \Nextras\Orm\Entity\Entity {
  const GENDER_MALE = "male";
  const GENDER_FEMALE = "female";
  
  /**
   * @param int $value
   * @return int
   */
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