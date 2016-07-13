<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

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
    Assert::type("\HeroesofAbenez\Entities\Guild", $guild);
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

/*$suit = new GuildModelTest($container->getService("hoa.model.guild"));
$suit->run();*/
?>