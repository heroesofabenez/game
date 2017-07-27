<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\PetType,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Pet Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 */
class Pet {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  public function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  /**
   * Get info about specified pet type
   */
  public function viewType(int $id): ?PetType {
    return $this->orm->petTypes->getById($id);
  }
  
  /**
   * Get specified user's active pet
   */
  public function getActivePet(int $user): ?PetEntity {
    return $this->orm->pets->getActivePet($user);
  }
  
  /**
   * Deploy a pet
   *
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetAlreadyDeployedException
   */
  public function deployPet(int $id): void {
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
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetNotDeployedException
   */
  public function discardPet(int $id): void {
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