<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

final class CharacterBuilderTest extends \Tester\TestCase {
  /** @var CharacterBuilder */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(CharacterBuilder::class);
  }

  public function testCreate() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var \HeroesofAbenez\Orm\CharacterClass $class */
    $class = $orm->classes->getById(1);
    /** @var \HeroesofAbenez\Orm\CharacterRace $race */
    $race = $orm->races->getById(1);
    $result = $this->model->create($class, $race);
    Assert::type("array", $result);
    Assert::same(12, $result["strength"]);
    Assert::same(10, $result["dexterity"]);
    Assert::same(13, $result["constitution"]);
    Assert::same(8, $result["intelligence"]);
    Assert::same(8, $result["charisma"]);
    $result = $this->model->create($class, $race, 5);
    Assert::type("array", $result);
    Assert::same(13, $result["strength"]);
    Assert::same(10, $result["dexterity"]);
    Assert::same(19, $result["constitution"]);
    Assert::same(8, $result["intelligence"]);
    Assert::same(8, $result["charisma"]);
  }
}

$test = new CharacterBuilderTest();
$test->run();
?>