<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\CharacterRace,
    HeroesofAbenez\Entities\CharacterClass,
    HeroesofAbenez\Entities\CharacterSpecialization;

class ProfileTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Profile */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Profile $model) {
    $this->model = $model;
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
    Assert::type("HeroesofAbenez\Entities\Pet", $result["pet"]);
  }
  
  /**
   * @return void
   */
  function testGetRacesList() {
    $list = $this->model->getRacesList();
    Assert::type("array", $list);
    Assert::type(CharacterRace::class, $list[1]);
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
    Assert::type("array", $list);
    Assert::type(CharacterClass::class, $list[1]);
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
   * @return void
   */
  function testGetSpecializationsList() {
    $list = $this->model->getSpecializationsList();
    Assert::type("array", $list);
    Assert::type(CharacterSpecialization::class, $list[1]);
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
}
?>