<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class ArenaPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    $this->checkAction("Arena:default");
  }
  
  public function testHeroes() {
    $this->checkAction("Arena:heroes");
  }
  
  public function testChampion() {
    $this->checkAction("Arena:champion", ["id" => 1]);
  }
}

$test = new ArenaPresenterTest;
$test->run();
?>