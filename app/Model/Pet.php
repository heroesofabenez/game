<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\PetType,
    HeroesofAbenez\Entities\Pet as PetEntity;

/**
 * Pet Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 */
class Pet {
  use \Nette\SmartObject;
  
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
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
        $return[$type->id] = new PetType($type);
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
  function viewType(int $id) {
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
  function getActivePet(int $user) {
    $activePet = $this->db->table("pets")->where("owner=$user")->where("deployed=1");
    if($activePet->count() == 1) {
      $pet = $activePet->fetch();
      $petType = $this->viewType($pet->type);
      $petName = ($pet->name === NULL) ? "Unnamed" : $petName = $pet->name . ",";
      $return = new PetEntity($user, $petType, $petName, $pet->deployed);
    } else {
      $return = false;
    }
    return $return;
  }
  
  /**
   * Deploy a pet
   * 
   * @param int $id
   * @return void
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetAlreadyDeployedException
   */
  function deployPet(int $id) {
    $pet = $this->db->table("pets")->get($id);
    if(!$pet) throw new PetNotFoundException;
    elseif($pet->owner != $this->user->id) throw new PetNotOwnedException;
    elseif($pet->deployed) throw new PetAlreadyDeployedException;
    $pets = $this->db->table("pets")
        ->where("owner", $this->user->id);
    foreach($pets as $pet) {
      if($pet->id == $id) continue;
      $this->db->query("UPDATE pets SET ? WHERE id=?", ["deployed" => 0], $pet->id);
    }
    $this->db->query("UPDATE pets SET ? WHERE id=?", ["deployed" => 1], $id);
  }
  
  /**
   * Discard a pet
   * 
   * @param int $id
   * @return void
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetNotDeployedException
   */
  function discardPet(int $id) {
    $pet = $this->db->table("pets")->get($id);
    if(!$pet) {
      throw new PetNotFoundException;
    } elseif($pet->owner != $this->user->id) {
      throw new PetNotOwnedException;
    } elseif(!$pet->deployed) {
      throw new PetNotDeployedException;
    }
    $data = ["deployed" => 0];
    $this->db->query("UPDATE pets SET ? WHERE id=?", $data, $id);
  }
}
?>