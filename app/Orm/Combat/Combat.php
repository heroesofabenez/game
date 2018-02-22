<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Combat
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $text
 * @property int $when
 */
class Combat extends \Nextras\Orm\Entity\Entity {
  public function onBeforeInsert() {
    $this->when = time();
  }
}
?>