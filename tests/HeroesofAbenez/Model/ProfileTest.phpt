<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\CharacterRace,
    HeroesofAbenez\Orm\CharacterClass,
    HeroesofAbenez\Orm\CharacterSpecialization,
    Nextras\Orm\Collection\ICollection;

class ProfileTest extends MT\TestCase {
  /** @var Profile */
  protected $model;
  
  function __construct(Profile $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->model->user = $user;
  }
  
  /**
   * @return void
   */
  function testIds() {
    $expected = 0;
    $actual = $this->model->getCharacterId("abc");
    Assert::same($expected, $actual);
    unset($expected, $actual);
    $expected = 1;
    $actual = $this->model->getCharacterGuild(1);
    Assert::same($expected, $actual);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $result = $this->model->view($id);
    Assert::type("array", $result);
    Assert::count(16, $result);
    Assert::same("male", $result["gender"]);
    Assert::type("int", $result["guild"]);
    Assert::null($result["specialization"]);
    Assert::type(\HeroesofAbenez\Orm\Pet::class, $result["pet"]);
  }
  
  /**
   * @return void
   */
  function testGetRacesList() {
    $list = $this->model->getRacesList();
    Assert::type(ICollection::class, $list);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetRace(int $id) {
    $result = $this->model->getRace($id);
    Assert::type(CharacterRace::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetRaceName(int $id) {
    $result = $this->model->getRaceName($id);
    Assert::type("string", $result);
  }
  
  /**
   * @return void
   */
  function testGetClassesList() {
    $list = $this->model->getClassesList();
    Assert::type(ICollection::class, $list);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetClass(int $id) {
    $result = $this->model->getClass($id);
    Assert::type(CharacterClass::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetClassName(int $id) {
    $result = $this->model->getClassName($id);
    Assert::type("string", $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetSpecialization(int $id) {
    $result = $this->model->getSpecialization($id);
    Assert::type(CharacterSpecialization::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetSpecializationName(int $id) {
    $result = $this->model->getSpecializationName($id);
    Assert::type("string", $result);
  }
  
  /**
   * @return void
   */
  function testGetStats() {
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
  }
}
?>