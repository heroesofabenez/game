<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\Npc as NPCEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\NpcDummy;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
class NPC {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  function __construct(\Nette\Caching\Cache $cache, ORM $orm) {
    $this->orm = $orm;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of npcs
   * 
   * @param int $stage Return npcs only from certain stage, 0 = all stages
   * @return NpcDummy[]
   */
  function listOfNpcs(int $stage = 0): array {
    $npcs = $this->cache->load("npcs", function(& $dependencies) {
      $return = [];
      $npcs = $this->orm->npcs->findAll();
      /** @var NPCEntity $npc */
      foreach($npcs as $npc) {
        $return[$npc->id] = new NpcDummy($npc);
      }
      return $return;
    });
    if($stage > 0) {
      /** @var NpcDummy $npc */
      foreach($npcs as $npc) {
        if($npc->stage !== $stage) {
          unset($npcs[$npc->id]);
        }
      }
    }
    return $npcs;
  }
  
  /**
   * Get info about specified npc
   * 
   * @param int $id Npc's id
   * @return NpcDummy|NULL
   */
  function view(int $id): ?NpcDummy {
    $npcs = $this->listOfNpcs();
    return Arrays::get($npcs, $id, NULL);
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