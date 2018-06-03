<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Npc as NPCEntity,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
final class NPC {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  /**
   * Get info about specified npc
   */
  public function view(int $id): ?NPCEntity {
    return $this->orm->npcs->getById($id);
  }
}
?>