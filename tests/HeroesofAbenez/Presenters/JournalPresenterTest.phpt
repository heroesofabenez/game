<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class JournalPresenterTest extends \Tester\TestCase {
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

  public function testFriends() {
    $this->checkAction("Journal:friends");
  }
}

$test = new JournalPresenterTest();
$test->run();
?>