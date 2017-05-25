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
class NPC {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  
  function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  /**
   * Get info about specified npc
   * 
   * @param int $id Npc's id
   * @return NPCEntity|NULL
   */
  function view(int $id): ?NPCEntity {
    return $this->orm->npcs->getById($id);
  }
  
  /**
   * Get name of specified npc
   * 
   * @param int $id Npc's id
   * @return string
   */
  function getNpcName(int $id): string {
    $npc = $this->view($id);
    if(is_null($npc)) {
      return "";
    } else {
      return $npc->name;
    }
  }
}
?>