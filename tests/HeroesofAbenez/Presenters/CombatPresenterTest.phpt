<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class CombatPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testView() {
    $this->checkForward("Combat:view", "Combat:notfound", ["id" => 5000]);
  }
}

$test = new CombatPresenterTest;
$test->run();
?>