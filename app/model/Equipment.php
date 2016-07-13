<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Equipment as EquipmentEntity;

/**
 * Equipment Model
 *
 * @author Jakub Konečný
 */
class Equipment {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  
  /**
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   * @param \Nette\Caching\Cache $cache
   */
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, \Nette\Caching\Cache $cache) {
    $this->db = $db;
    $this->user = $user;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of all equipment
   * 
   * @return EquipmentEntity[]
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
   * @return EquipmentEntity|bool
   */
  function view($id) {
    $equipments = $this->listOfEquipment();
    $item = Arrays::get($equipments, $id, false);
    return $item;
  }
}
?>