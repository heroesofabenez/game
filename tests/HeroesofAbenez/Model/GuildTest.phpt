<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Guild as GuildEntity;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

final class GuildTest extends \Tester\TestCase {
  use TCharacterControl;

  /** @var Guild */
  protected $model;
  
  public function setUp() {
    $this->model = $this->getService(Guild::class);
  }
  
  public function testGetGuildName() {
    Assert::same("", $this->model->getGuildName(5000));
    Assert::notSame("", $this->model->getGuildName(1));
  }
  
  public function testView() {
    Assert::null($this->model->view(5000));
    $guild = $this->model->view(1);
    Assert::type(GuildEntity::class, $guild);
    Assert::same("Dawn", $guild->name);
    Assert::type(\Nextras\Orm\Relationships\OneHasMany::class, $guild->members);
    Assert::same(2, $guild->members->countStored());
  }
  
  public function testCustomRankName() {
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
  public function getIds(): array {
    return [
      [1, 2,]
    ];
  }
  
  /**
   * @dataProvider getIds
   */
  public function testGuildMembers(int $guild) {
    $members = $this->model->guildMembers($guild, [], true);
    Assert::type("array", $members);
    Assert::type(\stdClass::class, $members[0]);
    Assert::type("string", $members[0]->customRankName);
    if($guild === 1) {
      Assert::same("Sun ruler", $members[0]->customRankName);
      Assert::count(2, $members);
    } else {
      Assert::same("", $members[0]->customRankName);
      Assert::count(1, $members);
    }
    $members = $this->model->guildMembers($guild, [1]);
    Assert::type("array", $members);
    Assert::count(1, $members);
  }

  public function testCreate() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() use($orm) {
      $guild = $orm->guilds->getById(1);
      $this->model->create(["name" => $guild->name]);
    }, NameInUseException::class);
    $this->preserveStats(["guild", "guildrank"], function() {
      /** @var \HeroesofAbenez\Orm\Model $orm */
      $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
      $user = $this->getCharacter();
      $data = ["name" => "abc", "description" => "."];
      $this->model->create($data);
      $guild = $orm->guilds->getByName($data["name"]);
      Assert::type(GuildEntity::class, $guild);
      Assert::same($data["name"], $guild->name);
      Assert::same($guild, $user->guild);
      $orm->guilds->remove($guild);
    });
  }
  
  public function testListOfGuilds() {
    $guilds = $this->model->listOfGuilds();
    Assert::type(ICollection::class, $guilds);
    Assert::count(3, $guilds);
  }

  public function testLeave() {
    Assert::exception(function() {
      $this->model->leave();
    }, GrandmasterCannotLeaveGuildException::class);
    $user = $this->getCharacter();
    $guild = $user->guild;
    $guildRank = $user->guildrank;
    $this->modifyCharacter(["guild" => null, "guildrank" => $guildRank], function() {
      Assert::exception(function() {
        $this->model->leave();
      }, NotInGuildException::class);
    });
    $this->modifyCharacter(["guild" => $guild, "guildrank" => 1], function() {
      $this->model->leave();
      $user = $this->getCharacter();
      Assert::null($user->guild);
      Assert::null($user->guildrank);
    });
  }

  public function testRename() {
    Assert::exception(function() {
      /** @var GuildEntity $guild */
      $guild = $this->model->view(2);
      $this->model->rename(1, $guild->name);
    }, NameInUseException::class);
    /** @var GuildEntity $guild */
    $guild = $this->model->view(1);
    $oldName = $guild->name;
    $this->model->rename($guild->id, "abc");
    Assert::same("abc", $guild->name);
    $this->model->rename($guild->id, $oldName);
  }

  public function testChangeDescription() {
    Assert::exception(function() {
      $this->model->changeDescription(50, "abc");
    }, GuildNotFoundException::class);
    /** @var GuildEntity $guild */
    $guild = $this->model->view(1);
    $oldDescription = $guild->description;
    $this->model->changeDescription($guild->id, "abc");
    Assert::same("abc", $guild->description);
    $this->model->changeDescription($guild->id, $oldDescription);
  }

  public function testJoin() {
    $this->preserveStats(["guild", "guildrank"], function() {
      $user = $this->getCharacter();
      $this->model->join($user->id, 2);
      Assert::same(2, $user->guild->id);
      Assert::same(1, $user->guildrank->id);
    });
  }
  
  public function testGetDefaultRankNames() {
    $names = $this->model->getDefaultRankNames();
    Assert::type("array", $names);
    Assert::count(7, $names);
    Assert::type("string", $names[1]);
  }

  /**
    * @dataProvider getIds
    */
  public function testGetCustomRankNames(int $guild) {
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

$test = new GuildTest();
$test->run();
?>