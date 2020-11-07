<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nette\Security\Identity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
final class UserManagerTest extends \Tester\TestCase {
  use TCharacterControl;

  /** @var UserManager */
  protected $model;
  
  public function setUp() {
    $this->model = $this->getService(UserManager::class);
  }
  
  public function testAuthenticate() {
    $identity = $this->model->authenticate([]);
    Assert::type(Identity::class, $identity);
    Assert::same(1, $identity->id);
  }
  
  public function testCreate() {
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
    $result = $this->model->create($data);
    Assert::type("array", $result);
    Assert::same($oldCount + 1, $orm->characters->findAll()->countStored());
    Assert::same("male", $result["gender"]);
    Assert::same(12, $result["strength"]);
    Assert::same(10, $result["dexterity"]);
    Assert::same(13, $result["constitution"]);
    Assert::same(8, $result["intelligence"]);
    Assert::same(8, $result["charisma"]);
    $newCharacter = $orm->characters->getByName($data["name"]);
    $orm->characters->removeAndFlush($newCharacter);
  }
}

$test = new UserManagerTest();
$test->run();
?>