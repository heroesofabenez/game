<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class EquipmentPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testView() {
    $this->checkAction("Equipment:view", ["id" => 1]);
    $this->checkForward("Equipment:view", "Equipment:notfound", ["id" => 5000]);
  }
}

$test = new EquipmentPresenterTest();
$test->run();
?>