<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Npc as NPCEntity;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
final class NPC {
  use \Nette\SmartObject;
  
  public function __construct(private readonly ORM $orm) {
  }
  
  /**
   * Get info about specified npc
   */
  public function view(int $id): ?NPCEntity {
    return $this->orm->npcs->getById($id);
  }
}
?>