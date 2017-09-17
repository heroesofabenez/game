<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class GuildPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    $this->checkAction("Guild:default");
  }
  
  public function testView() {
    $this->checkForward(":Guild:view", "Guild:notfound", ["id" => 0]);
    $this->checkForward(":Guild:view", "Guild:notfound", ["id" => 5000]);
    $this->checkAction(":Guild:view", ["id" => 1]);
  }
  
  public function testMembers() {
    $this->checkAction("Guild:members");
  }
  
  public function testManage() {
    $this->checkAction("Guild:manage");
  }
  
  public function testRename() {
    $this->checkAction("Guild:rename");
  }
  
  public function testDescription() {
    $this->checkAction("Guild:description");
  }
  
  public function testApplications() {
    $this->checkAction("Guild:applications");
  }
  
  public function testRankNames() {
    $this->checkAction("Guild:rankNames");
  }
}

$test = new GuildPresenterTest;
$test->run();
?>