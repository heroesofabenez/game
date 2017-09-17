<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

class JournalPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    $this->checkAction("Journal:default");
  }
  
  public function testInventory() {
    $this->checkAction("Journal:inventory");
  }
  
  public function testQuests() {
    $this->checkAction("Journal:quests");
  }
  
  public function testPets() {
    $this->checkAction("Journal:pets");
  }
}

$test = new JournalPresenterTest;
$test->run();
?>