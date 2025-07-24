<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Nette\Application\BadRequestException;
use Tester\Assert;

/**
 * @author Jakub Konečný
 * @skip
 */
final class RequestPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault(): void {
    $this->checkAction("Request:default");
  }
  
  public function testView(): void {
    $this->checkAction("Request:view", ["id" => 1]);
    Assert::exception(function() {
      $this->checkAction("Request:view", ["id" => 5000]);
    }, BadRequestException::class);
  }
}

$test = new RequestPresenterTest();
$test->run();
?>