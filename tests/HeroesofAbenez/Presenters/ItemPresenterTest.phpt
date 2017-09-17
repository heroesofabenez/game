<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

final class ItemPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testView() {
    $this->checkAction("Item:view", ["id" => 1]);
    $this->checkForward("Item:view", "Item:notfound", ["id" => 5000]);
  }
}

$test = new ItemPresenterTest;
$test->run();
?>