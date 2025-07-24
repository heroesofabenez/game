<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @skip
 */
final class TavernPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testLocal(): void {
    $this->checkAction("Tavern:local");
  }
  
  public function testGlobal(): void {
    $this->checkAction("Tavern:global");
  }
  
  public function testGuild(): void {
    $this->checkAction("Tavern:guild");
  }
}

$test = new TavernPresenterTest();
$test->run();
?>