<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert,
    Nette\Application\BadRequestException;

final class NpcPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Npc:default");
    }, BadRequestException::class);
  }
  
  public function testView() {
    $this->checkAction("Npc:view", ["id" => 1]);
    $this->checkForward("Npc:view", "Npc:notfound", ["id" => 5000]);
  }
  
  public function testTalk() {
    $this->checkAction("Npc:talk", ["id" => 1]);
    $this->checkForward("Npc:talk", "Npc:notfound", ["id" => 5000]);
  }
  
  public function testQuests() {
    $this->checkAction("Npc:quests", ["id" => 1]);
    $this->checkForward("Npc:quests", "Npc:notfound", ["id" => 5000]);
  }
  
  public function testTrade() {
    $this->checkRedirect("Npc:trade", "/npc/1", ["id" => 1]);
  }
}

$test = new NpcPresenterTest();
$test->run();
?>