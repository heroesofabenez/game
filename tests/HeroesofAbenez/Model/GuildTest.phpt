<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Guild as GuildEntity,
    Nextras\Orm\Collection\ICollection;

class GuildTest extends MT\TestCase {
  /** @var Guild */
  protected $model;
  
  function __construct(Guild $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfGuilds() {
    $result = $this->model->listOfGuilds();
    Assert::type(ICollection::class, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $guild = $this->model->view($id);
    Assert::type(GuildEntity::class, $guild);
    Assert::same("Dawn", $guild->name);
    Assert::type(\Nextras\Orm\Relationships\OneHasMany::class, $guild->members);
    Assert::same(2, $guild->members->countStored());
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
        if($guildId === 1 AND $rankId === 7) {
          Assert::same("Sun ruler", $rankName);
        } elseif($guildId === 1 AND $rankId === 1) {
          Assert::same("Sun observer", $rankName);
        } else {
          Assert::same("", $rankName);
        }
      }
    }
  }
  
  /**
   * @param int $guild
   * @data(1,2)
   * @return void
   */
  function testGuildMembers(int $guild) {
    $members = $this->model->guildMembers($guild, [], true);
    Assert::type("array", $members);
    Assert::type("stdClass", $members[0]);
    Assert::type("string", $members[0]->customRankName);
    if($guild === 1) {
      Assert::same("Sun ruler", $members[0]->customRankName);
    } else {
      Assert::same("", $members[0]->customRankName);
    }
  }

  /**
   * @return void
   */
  function testGetDefaultRankNames() {
    $names = $this->model->getDefaultRankNames();
    Assert::type("array", $names);
    Assert::count(7, $names);
    Assert::type("string", $names[1]);
  }

  /**
    * @param int $guild
    * @data(1,2)
    * @return void
    */
  function testGetCustomRankNames(int $guild) {
    $names = $this->model->getCustomRankNames($guild);
    Assert::type("array", $names);
    if($guild > 1) {
      Assert::count(0, $names);
    } else {
      Assert::count(7, $names);
      foreach($names as $name) {
        Assert::type("string", $name);
      }
      Assert::same("Sun ruler", $names[7]);
      Assert::same("Sun observer", $names[1]);
    }
  }
}
?>