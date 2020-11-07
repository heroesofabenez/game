<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\BadRequestException;
use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
final class CombatPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testView() {
    Assert::exception(function() {
      $this->checkAction("Combat:view", ["id" => 5000]);
    }, BadRequestException::class);
  }
}

$test = new CombatPresenterTest();
$test->run();
?>