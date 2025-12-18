<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Character;
use Tester\Assert;
use Nette\Security\SimpleIdentity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class UserManagerTest extends \Tester\TestCase
{
    use TCharacterControl;

    private UserManager $model;

    public function setUp(): void
    {
        $this->model = $this->getService(UserManager::class); // @phpstan-ignore assign.propertyType
    }

    public function testAuthenticate(): void
    {
        $identity = $this->model->authenticate("", "");
        Assert::type(SimpleIdentity::class, $identity);
        Assert::same(1, $identity->id);
        Assert::same(["grandmaster",], $identity->roles);
        Assert::same("James The Invisible", $identity->name);
        Assert::same(2, $identity->race);
        Assert::same(Character::GENDER_MALE, $identity->gender);
        Assert::same(3, $identity->class);
        Assert::null($identity->specialization);
        Assert::same(3, $identity->level);
        Assert::same(1, $identity->stage);
        Assert::same(0, $identity->white_karma);
        Assert::same(0, $identity->dark_karma);
        Assert::same(1, $identity->guild);
    }

    public function testCreate(): void
    {
        /** @var \HeroesofAbenez\Orm\Model $orm */
        $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
        $oldCount = $orm->characters->findAll()->countStored();
        $character = $this->getCharacter();
        $data = [
            "name" => $character->name, "gender" => 1, "race" => 1, "class" => 1,
        ];
        Assert::null($this->model->create($data));
        Assert::same($oldCount, $orm->characters->findAll()->countStored());
        $data["name"] = "abc";
        /** @var array $result */
        $result = $this->model->create($data);
        Assert::type("array", $result);
        Assert::same($oldCount + 1, $orm->characters->findAll()->countStored());
        Assert::same($data["name"], $result["name"]);
        Assert::same($data["class"], $result["class"]);
        Assert::same($data["race"], $result["race"]);
        Assert::same("male", $result["gender"]);
        Assert::same(12, $result["strength"]);
        Assert::same(10, $result["dexterity"]);
        Assert::same(13, $result["constitution"]);
        Assert::same(8, $result["intelligence"]);
        Assert::same(8, $result["charisma"]);
        /** @var Character $newCharacter */
        $newCharacter = $orm->characters->getByName($data["name"]);
        $orm->characters->removeAndFlush($newCharacter);
    }
}

$test = new UserManagerTest();
$test->run();
