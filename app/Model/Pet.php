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
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\Nette\Caching\Cache $cache, ORM $orm) {
    $this->cache = $cache;
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
    $pet = $this->orm->pets->getActivePet($user);
    if(is_null($pet)) {
      return NULL;
    }
    $type = new PetTypeDummy($pet->type);
    $name = $pet->name ?? "Unnamed";
    return new PetEntity($pet->id, $type, $name, $pet->deployed);
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
    $pet = $this->orm->pets->getById($id);
    if(is_null($pet)) {
      throw new PetNotFoundException;
    } elseif($pet->owner->id !== $this->user->id) {
      throw new PetNotOwnedException;
    } elseif($pet->deployed) {
      throw new PetAlreadyDeployedException;
    }
    $pets = $this->orm->pets->findByOwner($this->user->id);
    /** @var \HeroesofAbenez\Orm\Pet $pet */
    foreach($pets as $pet) {
      $pet->deployed = ($pet->id === $id);
      $this->orm->pets->persist($pet);
    }
    $this->orm->pets->flush();
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
    $pet = $this->orm->pets->getById($id);
    if(is_null($pet)) {
      throw new PetNotFoundException;
    } elseif($pet->owner->id !== $this->user->id) {
      throw new PetNotOwnedException;
    } elseif(!$pet->deployed) {
      throw new PetNotDeployedException;
    }
    $pet->deployed = false;
    $this->orm->pets->persistAndFlush($pet);
  }
}
?>