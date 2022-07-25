<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\Combat as CombatEntity;

/**
 * Combat Log
 *
 * @author Jakub Konečný
 */
final class CombatLogManager {
  use \Nette\SmartObject;

  private ORM $orm;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  /**
   * Load specified combat from database
   */
  public function read(int $id): ?CombatEntity {
    return $this->orm->combats->getById($id);
  }
  
  /**
   * Log new combat
   * 
   * @param string $text Combat log
   * @return int New record's id
   */
  public function write(string $text): int {
    $combat = new CombatEntity();
    $combat->text = $text;
    $this->orm->combats->persistAndFlush($combat);
    return $combat->id;
  }
}
?>