<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterRace;
use Nette\Security\SimpleIdentity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\Character;
use Nette\Security\IIdentity;

/**
 * Authenticator for the game
 *
 * @author Jakub Konečný
 */
final readonly class UserManager implements \Nette\Security\Authenticator
{
    public function __construct(
        private ORM $orm,
        private UserToCharacterMapper $userToCharacterMapper,
        private CharacterBuilder $cb
    ) {
    }

    /**
     * Logins the user
     */
    public function authenticate(string $user, string $password): IIdentity
    {
        $uid = $this->userToCharacterMapper->getRealId();
        if ($uid === UserToCharacterMapper::USER_ID_NOT_LOGGED_IN) {
            return new SimpleIdentity(UserToCharacterMapper::USER_ID_NOT_LOGGED_IN, "guest");
        }
        $char = $this->orm->characters->getByOwner($uid);
        if ($char === null) {
            return new SimpleIdentity(UserToCharacterMapper::USER_ID_NO_CHARACTER, "guest");
        }
        $data = [
            "name" => $char->name, "race" => $char->race->id, "gender" => $char->gender,
            "class" => $char->class->id,
            "specialization" => ($char->specialization !== null) ? $char->specialization->id : null,
            "level" => $char->level, "stage" => ($char->currentStage !== null) ? $char->currentStage->id : null,
            "white_karma" => $char->whiteKarma, "dark_karma" => $char->darkKarma,
        ];
        $data["guild"] = 0;
        $role = "player";
        if ($char->guild !== null) {
            $data["guild"] = $char->guild->id;
            $role = ($char->guildrank !== null) ? $char->guildrank->name : "";
        }
        $char->lastActive = new \DateTimeImmutable();
        $this->orm->characters->persistAndFlush($char);
        return new SimpleIdentity($char->id, $role, $data);
    }

    /**
     * Creates new character
     *
     * @return array{name: string, owner: int, gender: string, class: int, race: int, strength: int, dexterity: int, constitution: int, intelligence: int, charisma: int}|null Stats of new character
     */
    public function create(array $values): ?array
    {
        $character = $this->orm->characters->getByName($values["name"]);
        if ($character !== null) {
            return null;
        }

        /** @var CharacterClass $class */
        $class = $this->orm->classes->getById($values["class"]);
        /** @var CharacterRace $race */
        $race = $this->orm->races->getById($values["race"]);
        $data = $this->cb->create($class, $race);
        $data["strength"] = (int) $data["strength"];
        $data["dexterity"] = (int) $data["dexterity"];
        $data["constitution"] = (int) $data["constitution"];
        $data["intelligence"] = (int) $data["intelligence"];
        $data["charisma"] = (int) $data["charisma"];
        $data["name"] = $values["name"];
        $data["owner"] = $this->userToCharacterMapper->getRealId();
        $data["gender"] = ($values["gender"] === 1) ? "male" : "female";
        $data["class"] = $class;
        $data["race"] = $race;

        $character = new Character();
        $this->orm->characters->attach($character);
        foreach ($data as $key => $value) {
            $character->$key = $value;
        }
        $this->orm->characters->persistAndFlush($character);

        $data["class"] = $values["class"];
        $data["race"] = $values["race"];

        return $data;
    }
}
