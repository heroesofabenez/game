<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class GuildPresenterTest extends \Tester\TestCase {
  use TPresenter;
  use \HeroesofAbenez\Model\TCharacterControl;
  
  public function testDefault() {
    $this->checkAction("Guild:default");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:default", "Guild:noguild");
    });
  }
  
  public function testView() {
    $this->checkForward("Guild:view", "Guild:notfound", ["id" => 0]);
    $this->checkForward("Guild:view", "Guild:notfound", ["id" => 5000]);
    $this->checkAction("Guild:view", ["id" => 1]);
  }
  
  public function testMembers() {
    $this->checkAction("Guild:members");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:members", "Guild:noguild");
    });
  }
  
  public function testCreate() {
    $this->checkForward("Guild:create", "Guild:default");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkAction("Guild:create");
    });
  }
  
  public function testJoin() {
    $this->checkForward("Guild:join", "Guild:default");
  }

  public function testLeave() {
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:leave", "Guild:noguild");
    });
  }
  
  public function testManage() {
    $this->checkAction("Guild:manage");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:manage", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:manage", "/guild");
    });
  }
  
  public function testRename() {
    $this->checkAction("Guild:rename");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:rename", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:rename", "/guild");
    });
  }

  public function testDissolve() {
    $this->checkAction("Guild:dissolve");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:dissolve", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:dissolve", "/guild");
    });
  }
  
  public function testDescription() {
    $this->checkAction("Guild:description");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:description", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:description", "/guild");
    });
  }
  
  public function testApplications() {
    $this->checkAction("Guild:applications");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:applications", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:applications", "/guild");
    });
  }
  
  public function testRankNames() {
    $this->checkAction("Guild:rankNames");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:rankNames", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:rankNames", "/guild");
    });
  }
}

$test = new GuildPresenterTest();
$test->run();
?>