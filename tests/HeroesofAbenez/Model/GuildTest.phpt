<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Guild as GuildEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class GuildTest extends \Tester\TestCase {
  /** @var Guild */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->model = $this->getService(Guild::class);
  }
  
  /**
   * @return void
   */
  function testView() {
    $guild = $this->model->view(1);
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
   * @return int[]
   */
  function getIds(): array {
    return [
      [1, 2,]
    ];
  }
  
  /**
   * @param int $guild
   * @dataProvider getIds
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
   * @dataProvider getIds
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

$test = new GuildTest;
$test->run();
?>