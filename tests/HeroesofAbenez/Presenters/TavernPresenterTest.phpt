<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class TavernPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testLocal() {
    $this->checkAction("Tavern:local");
  }
  
  public function testGlobal() {
    $this->checkAction("Tavern:global");
  }
  
  public function testGuild() {
    $this->checkAction("Tavern:guild");
  }
}

$test = new TavernPresenterTest;
$test->run();
?>