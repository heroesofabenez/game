<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\PetType,
    HeroesofAbenez\Entities\Pet as PetEntity;

/**
 * Pet Model
 *
 * @author Jakub Konečný
 */
class Pet {
  use \Nette\SmartObject;
  
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  /**
   * Get list of all pet types
   * 
   * @return PetType[]
   */
  function listOfTypes() {
    $return = [];
    $types = $this->cache->load("pet_types");
    if($types === NULL) {
      $types = $this->db->table("pet_types");
      foreach($types as $type) {
        $return[] = new PetType($type);
      }
      $this->cache->save("pet_types", $return);
    } else {
      $return = $types;
    }
    return $return;
  }
  
  /**
   * Get info about specified pet type
   * 
   * @param int $id
   * @return PetType
   */
  function viewType($id) {
    $types = $this->listOfTypes();
    $type = Arrays::get($types, $id, false);
    return $type;
  }
  
  /**
   * Get specified user's active pet
   * 
   * @param int $user User's id
   * @return PetEntity|bool
   */
  function getActivePet($user) {
    $activePet = $this->db->table("pets")->where("owner=$user")->where("deployed=1");
    if($activePet->count() == 1) {
      $pet = $activePet->fetch();
      $petType = $this->viewType($pet->type);
      $petName = ($pet->name === NULL) ? "Unnamed" : $petName = $pet->name . ",";
      $return = new PetEntity($user, $petType, $petName);
    } else {
      $return = false;
    }
    return $return;
  }
}
?>