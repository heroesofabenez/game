<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
final class HomepagePresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    $this->checkAction("Homepage:default");
  }
}

$test = new HomepagePresenterTest();
$test->run();
?>