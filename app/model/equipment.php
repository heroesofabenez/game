<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays;

/**
 * Data structure for equipment
 * 
 * @author Jakub Konečný
 */
class Equipment extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  /** @var string */
  public $description;
  /** @var string */
  public $slot;
  /** @var string */
  public $type;
  /** @var int */
  public $required_lvl;
  /** @var int */
  public $required_class;
  /** @var int */
  public $price;
  /** @var int */
  public $strength;
  /** @var int */
  public $durability;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "equipment") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}

/**
 * Equipment Model
 *
 * @author Jakub Konečný
 */
class EquipmentModel {
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
        $return[$eq->id] = new Equipment($eq);
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