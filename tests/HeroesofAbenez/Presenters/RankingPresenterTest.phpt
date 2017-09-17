<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class RankingPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testCharacters() {
    $this->checkAction("Ranking:characters");
  }
  
  public function testGuilds() {
    $this->checkAction("Ranking:guilds");
  }
}

$test = new RankingPresenterTest;
$test->run();
?>