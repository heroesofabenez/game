<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Guild;

class GuildModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfGuilds() {
    $result = $this->model->listOfGuilds();
    Assert::type("array", $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGuildData($id) {
    $guild = $this->model->guildData($id);
    Assert::type(Guild::class, $guild);
    Assert::same("Dawn", $guild->name);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $guild = $this->model->view($id);
    Assert::type("array", $guild);
    Assert::same("Dawn", $guild["name"]);
    Assert::type("array", $guild["members"]);
    Assert::count(2, $guild["members"]);
  }
}
?>