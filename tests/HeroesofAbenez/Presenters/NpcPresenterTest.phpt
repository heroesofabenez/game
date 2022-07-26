<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;
use Nette\Application\BadRequestException;

/**
 * @author Jakub Konečný
 */
final class NpcPresenterTest extends \Tester\TestCase {
  use TPresenter;
  use \HeroesofAbenez\Model\TCharacterControl;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Npc:default");
    }, BadRequestException::class);
  }
  
  public function testView() {
    $this->checkAction("Npc:view", ["id" => 1]);
    $this->checkAction("Npc:view", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:view", ["id" => 5000]);
    }, BadRequestException::class);
  }
  
  public function testTalk() {
    $this->checkAction("Npc:talk", ["id" => 1]);
    $this->checkForward("Npc:talk", "Npc:unavailable", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:talk", ["id" => 5000]);
    }, BadRequestException::class);
  }
  
  public function testQuests() {
    $this->checkAction("Npc:quests", ["id" => 1]);
    $this->checkForward("Npc:quests", "Npc:unavailable", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:quests", ["id" => 5000]);
    }, BadRequestException::class);
  }
  
  public function testTrade() {
    $this->checkRedirect("Npc:trade", "/npc/1", ["id" => 1]);
    $this->checkForward("Npc:trade", "Npc:unavailable", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:trade", ["id" => 5000]);
    }, BadRequestException::class);
    $this->modifyCharacter(["currentStage" => 3], function() {
      $this->checkAction("Npc:trade", ["id" => 2]);
    });
  }
  
  public function testFight() {
    $this->checkRedirect("Npc:fight", "/npc/1", ["id" => 1]);
    $this->checkForward("Npc:fight", "Npc:unavailable", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:fight", ["id" => 5000]);
    }, BadRequestException::class);
  }

  public function testRepair() {
    $this->checkRedirect("Npc:repair", "/npc/1", ["id" => 1]);
    $this->checkForward("Npc:repair", "Npc:unavailable", ["id" => 2]);
    Assert::exception(function() {
      $this->checkAction("Npc:repair", ["id" => 5000]);
    }, BadRequestException::class);
    $this->modifyCharacter(["currentStage" => 3], function() {
      $this->checkAction("Npc:repair", ["id" => 2]);
    });
  }
}

$test = new NpcPresenterTest();
$test->run();
?>