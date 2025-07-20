<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class TrainingPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault(): void {
    $this->checkAction("Training:default");
  }
}

$test = new TrainingPresenterTest();
$test->run();
?>