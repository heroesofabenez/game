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
final class ItemPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testView() {
    $this->checkAction("Item:view", ["id" => 1]);
    Assert::exception(function() {
      $this->checkAction("Item:view", ["id" => 5000]);
    }, BadRequestException::class);
  }
}

$test = new ItemPresenterTest();
$test->run();
?>