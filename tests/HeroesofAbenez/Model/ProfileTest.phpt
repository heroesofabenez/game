<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterSpecialization;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

final class ProfileTest extends \Tester\TestCase {
  /** @var Profile */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Profile::class);
  }
  
  public function testGetRacesList() {
    $list = $this->model->getRacesList();
    Assert::type(ICollection::class, $list);
  }
  
  public function testGetRace() {
    Assert::type(CharacterRace::class, $this->model->getRace(1));
    Assert::null($this->model->getRace(5000));
  }
  
  public function testGetRaceName() {
    Assert::notSame("", $this->model->getRaceName(1));
    Assert::same("", $this->model->getRaceName(5000));
  }
  
  public function testGetClassesList() {
    $list = $this->model->getClassesList();
    Assert::type(ICollection::class, $list);
  }
  
  public function testGetClass() {
    Assert::type(CharacterClass::class, $this->model->getClass(1));
    Assert::null($this->model->getClass(5000));
  }
  
  public function testGetClassName() {
    Assert::notSame("", $this->model->getClassName(1));
    Assert::same("", $this->model->getClassName(5000));
  }
  
  public function testGetSpecialization() {
    Assert::type(CharacterSpecialization::class, $this->model->getSpecialization(1));
    Assert::null($this->model->getSpecialization(5000));
  }
  
  public function testGetSpecializationName() {
    Assert::notSame("", $this->model->getSpecializationName(1));
    Assert::same("", $this->model->getSpecializationName(5000));
  }
  
  public function testGetCharacterId() {
    Assert::same(0, $this->model->getCharacterId("abc"));
    Assert::same(1, $this->model->getCharacterId("James The Invisible"));
  }
  
  public function testGetCharacterName() {
    Assert::same("", $this->model->getCharacterName(0));
    Assert::same("James The Invisible", $this->model->getCharacterName(1));
  }
  
  public function testGetCharacterGuild() {
    Assert::same(0, $this->model->getCharacterGuild(0));
    Assert::same(1, $this->model->getCharacterGuild(1));
  }
  
  public function testView() {
    Assert::null($this->model->view(5000));
    $result = $this->model->view(1);
    Assert::type("array", $result);
    Assert::count(17, $result);
    Assert::same("male", $result["gender"]);
    Assert::type("int", $result["guild"]);
    Assert::null($result["specialization"]);
    Assert::type(\HeroesofAbenez\Orm\Pet::class, $result["pet"]);
  }
  
  public function testGetStats() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
  }
}

$test = new ProfileTest();
$test->run();
?>