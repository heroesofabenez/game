<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\EquipmentEntity;

/**
 * Equipment Model
 *
 * @author Jakub Konečný
 */
class Equipment {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, \Nette\Caching\Cache $cache) {
    $this->db = $db;
    $this->user = $user;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of all equipment
   * 
   * @return array
   */
  function listOfEquipment() {
    $return = array();
    $equipments = $this->cache->load("equipment");
    if($equipments === NULL) {
      $equipments = $this->db->table("equipment");
      foreach($equipments as $eq) {
        $return[$eq->id] = new EquipmentEntity($eq);
      }
      $this->cache->save("equipment", $return);
    } else {
      $return = $equipments;
    }
    return $return;
  }
  
  /**
   * Gets data about specified equipment
   * @param int $id
   */
  function view($id) {
    $equipments = $this->listOfEquipment();
    $item = Arrays::get($equipments, $id, false);
    return $item;
  }
}
?>