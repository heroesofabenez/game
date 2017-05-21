<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\Combat as CombatEntity;

/**
 * Combat Log
 *
 * @author Jakub Konečný
 */
class CombatLogManager {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  
  function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  /**
   * Load specified combat from database
   * 
   * @param int $id Combat's id
   * @return CombatEntity|NULL
   */
  function read(int $id): ?CombatEntity {
    return $this->orm->combats->getById($id);
  }
  
  /**
   * Log new combat
   * 
   * @param string $text Combat log
   * @return int New record's id
   */
  function write(string $text): int {
    $combat = new CombatEntity;
    $combat->text = $text;
    $this->orm->combats->persistAndFlush($combat);
    return $combat->id;
  }
}
?>