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
    Assert::exception(function() use($class, $race,  $orm) {
      $specialization = $orm->specializations->getById(8);
      $this->model->create($class, $race, CharacterBuilder::SPECIALIZATION_LEVEL - 1, $specialization);
    }, CannotChooseSpecializationException::class);
    Assert::exception(function() use($class, $race) {
      $this->model->create($class, $race, CharacterBuilder::SPECIALIZATION_LEVEL);
    }, SpecializationNotChosenException::class);
    Assert::exception(function() use($class, $race,  $orm) {
      $specialization = $orm->specializations->getById(8);
      $this->model->create($class, $race, CharacterBuilder::SPECIALIZATION_LEVEL, $specialization);
    }, SpecializationNotAvailableException::class);
    $specialization = $orm->specializations->getById(1);
    $result = $this->model->create($class, $race, CharacterBuilder::SPECIALIZATION_LEVEL, $specialization);
    Assert::type("array", $result);
    Assert::same(17, $result["strength"]);
    Assert::same(12, $result["dexterity"]);
    Assert::same(32, $result["constitution"]);
    Assert::same(8, $result["intelligence"]);
    Assert::same(9, $result["charisma"]);
  }
}

$test = new CharacterBuilderTest();
$test->run();
?>