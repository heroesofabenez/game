<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\PetType;
use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Pet Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 */
final class Pet
{
    use \Nette\SmartObject;

    private \Nette\Security\User $user;

    public function __construct(private readonly ORM $orm)
    {
    }

    protected function setUser(\Nette\Security\User $user): void
    {
        $this->user = $user;
    }

    /**
     * Get info about specified pet type
     */
    public function viewType(int $id): ?PetType
    {
        return $this->orm->petTypes->getById($id);
    }

    public function canDeployPet(PetEntity $pet): bool
    {
        if ($this->user->identity->level < $pet->type->requiredLevel) {
            return false;
        } elseif ($pet->type->requiredClass !== null && $pet->type->requiredClass->id !== $this->user->identity->class) {
            return false;
        } elseif ($pet->type->requiredRace !== null && $pet->type->requiredRace->id !== $this->user->identity->race) {
            return false;
        }
        return true;
    }

    /**
     * Deploy a pet
     *
     * @throws PetNotFoundException
     * @throws PetNotOwnedException
     * @throws PetAlreadyDeployedException
     * @throws PetNotDeployableException
     */
    public function deployPet(int $id): void
    {
        $pet = $this->orm->pets->getById($id);
        if ($pet === null) {
            throw new PetNotFoundException();
        } elseif ($pet->owner->id !== $this->user->id) {
            throw new PetNotOwnedException();
        } elseif ($pet->deployed) {
            throw new PetAlreadyDeployedException();
        } elseif (!$this->canDeployPet($pet)) {
            throw new PetNotDeployableException();
        }
        unset($pet);
        $pets = $this->orm->pets->findByOwner($this->user->id);
        foreach ($pets as $pet) {
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
    public function discardPet(int $id): void
    {
        $pet = $this->orm->pets->getById($id);
        if ($pet === null) {
            throw new PetNotFoundException();
        } elseif ($pet->owner->id !== $this->user->id) {
            throw new PetNotOwnedException();
        } elseif (!$pet->deployed) {
            throw new PetNotDeployedException();
        }
        $pet->deployed = false;
        $this->orm->pets->persistAndFlush($pet);
    }

    /**
     * Give the player a pet of specified type (if they do not own one already)
     */
    public function givePet(int $type): void
    {
        $petType = $this->viewType($type);
        if ($petType === null) {
            return;
        }
        if ($this->orm->pets->getByTypeAndOwner($petType, $this->user->id) !== null) {
            return;
        }
        $pet = new PetEntity();
        $this->orm->pets->attach($pet);
        $pet->owner = $this->user->id;
        $pet->type = $petType;
        $this->orm->pets->persistAndFlush($pet);
    }
}
