<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class JournalPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault(): void {
    $this->checkAction("Journal:default");
  }
  
  public function testInventory(): void {
    $this->checkAction("Journal:inventory");
  }
  
  public function testQuests(): void {
    $this->checkAction("Journal:quests");
  }
  
  public function testPets(): void {
    $this->checkAction("Journal:pets");
  }

  public function testFriends(): void {
    $this->checkAction("Journal:friends");
  }
}

$test = new JournalPresenterTest();
$test->run();
?>