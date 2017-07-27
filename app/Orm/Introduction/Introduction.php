<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Introduction
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property CharacterRace $race {m:1 CharacterRace::$intro}
 * @property CharacterClass $class {m:1 CharacterClass::$intro}
 * @property int $part
 * @property string $text
 */
class Introduction extends \Nextras\Orm\Entity\Entity {
  protected function setterPart(int $value): int {
    if($value < 1) {
      return 1;
    } elseif($value > 9) {
      return 9;
    } else {
      return $value;
    }
  }
}
?>