<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\BadRequestException;
use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @skip
 */
final class GuildPresenterTest extends \Tester\TestCase {
  use TPresenter;
  use \HeroesofAbenez\Model\TCharacterControl;
  
  public function testDefault(): void {
    $this->checkAction("Guild:default");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:default", "Guild:noguild");
    });
  }
  
  public function testView(): void {
    Assert::exception(function() {
      $this->checkAction("Guild:view", ["id" => 5000]);
    }, BadRequestException::class);
    $this->checkAction("Guild:view", ["id" => 1]);
  }
  
  public function testMembers(): void {
    $this->checkAction("Guild:members");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:members", "Guild:noguild");
    });
  }
  
  public function testCreate(): void {
    $this->checkForward("Guild:create", "Guild:default");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkAction("Guild:create");
    });
  }
  
  public function testJoin(): void {
    $this->checkForward("Guild:join", "Guild:default");
  }

  public function testLeave(): void {
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:leave", "Guild:noguild");
    });
  }
  
  public function testManage(): void {
    $this->checkAction("Guild:manage");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:manage", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:manage", "/guild");
    });
  }
  
  public function testRename(): void {
    $this->checkAction("Guild:rename");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:rename", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:rename", "/guild");
    });
  }

  public function testDissolve(): void {
    $this->checkAction("Guild:dissolve");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:dissolve", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:dissolve", "/guild");
    });
  }
  
  public function testDescription(): void {
    $this->checkAction("Guild:description");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:description", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:description", "/guild");
    });
  }
  
  public function testApplications(): void {
    $this->checkAction("Guild:applications");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:applications", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:applications", "/guild");
    });
  }
  
  public function testRankNames(): void {
    $this->checkAction("Guild:rankNames");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:rankNames", "Guild:noguild");
    });
    $this->modifyCharacter(["guildrank" => 1], function() {
      $this->checkRedirect("Guild:rankNames", "/guild");
    });
  }

  public function testDonate(): void {
    $this->checkAction("Guild:donate");
    $this->modifyCharacter(["guild" => null], function() {
      $this->checkForward("Guild:donate", "Guild:noguild");
    });
  }
}

$test = new GuildPresenterTest();
$test->run();
?>