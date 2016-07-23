<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\CharacterRace,
    HeroesofAbenez\Entities\CharacterClass;

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
  function testView($id) {
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
  function testGetRace($id) {
    $result = $this->model->getRace($id);
    Assert::type(CharacterRace::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetRaceName($id) {
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
  function testGetClass($id) {
    $result = $this->model->getClass($id);
    Assert::type(CharacterClass::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetClassName($id) {
    $result = $this->model->getClassName($id);
    Assert::type("string", $result);
  }
}

/*$suit = new ProfileTest($container->getService("hoa.model.profile"));
$suit->run();*/
?>