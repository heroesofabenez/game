<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\PetType,
    HeroesofAbenez\Entities\Pet as PetEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\PetTypeDummy;

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
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, ORM $orm) {
    $this->cache = $cache;
    $this->db = $db;
    $this->orm = $orm;
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  /**
   * Get list of all pet types
   * 
   * @return PetTypeDummy[]
   */
  function listOfTypes() {
    $types = $this->cache->load("pet_types", function(& $dependencies) {
      $return = [];
      $types = $this->orm->petTypes->findAll();
      /** @var PetType $type */
      foreach($types as $type) {
        $return[$type->id] = new PetTypeDummy($type);
      }
      return $return;
    });
    return $types;
  }
  
  /**
   * Get info about specified pet type
   * 
   * @param int $id
   * @return PetTypeDummy|NULL
   */
  function viewType(int $id): ?PetTypeDummy {
    $types = $this->listOfTypes();
    return Arrays::get($types, $id, NULL);
  }
  
  /**
   * Get specified user's active pet
   * 
   * @param int $user User's id
   * @return PetEntity|NULL
   */
  function getActivePet(int $user): ?PetEntity {
    $activePet = $this->db->table("pets")->where("owner=$user")->where("deployed=1");
    if($activePet->count() == 1) {
      $pet = $activePet->fetch();
      $petType = $this->viewType($pet->type);
      $petName = ($pet->name === NULL) ? "Unnamed" : $petName = $pet->name . ",";
      $return = new PetEntity($user, $petType, $petName, $pet->deployed);
    } else {
      $return = NULL;
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
  function deployPet(int $id): void {
    $pet = $this->db->table("pets")->get($id);
    if(!$pet) {
      throw new PetNotFoundException;
    } elseif($pet->owner != $this->user->id) {
      throw new PetNotOwnedException;
    } elseif($pet->deployed) {
      throw new PetAlreadyDeployedException;
    }
    $pets = $this->db->table("pets")
        ->where("owner", $this->user->id);
    foreach($pets as $pet) {
      if($pet->id == $id) {
        continue;
      }
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
  function discardPet(int $id): void {
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