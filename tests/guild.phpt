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
    $guild = $this->model->view($id, [], true);
    Assert::type("array", $guild);
    Assert::same("Dawn", $guild["name"]);
    Assert::type("array", $guild["members"]);
    Assert::count(2, $guild["members"]);
  }
  
  /**
   * @return void
   */
  function testCustomRankName() {
    $guildIds = [1, 2];
    $rankIds = [1, 7];
    foreach($guildIds as $guildId) {
      foreach($rankIds as $rankId) {
        $rankName = $this->model->getCustomRankName($guildId, $rankId);
        if($guildId === 1 AND $rankId === 7) Assert::same("Sun ruler", $rankName);
        elseif($guildId === 1 AND $rankId === 1) Assert::same("Sun follower", $rankName);
        else Assert::same("", $rankName);
      }
    }
  }
  
  /**
   * @param int $guild
   * @data(1,2)
   * @return void
   */
  function testGuildMemembers($guild) {
    $members = $this->model->guildMembers($guild, [], true);
    Assert::type("array", $members);
    Assert::type("stdClass", $members[0]);
    Assert::type("string", $members[0]->customRankName);
    if($guild === 1) Assert::same("Sun ruler", $guild["members"][0]->customRankName);
    else Assert::same("", $members[0]->customRankName);
  }
}
?>