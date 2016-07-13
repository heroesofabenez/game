<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

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
}

/*$suit = new ProfileTest($container->getService("hoa.model.profile"));
$suit->run();*/
?>