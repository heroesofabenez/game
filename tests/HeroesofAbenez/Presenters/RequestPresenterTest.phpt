<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Nette\Application\BadRequestException;
use Tester\Assert;

final class RequestPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Request:default");
    }, BadRequestException::class);
  }
  
  public function testView() {
    $this->checkAction("Request:view", ["id" => 1]);
    $this->checkForward("Request:view", "Request:notfound", ["id" => 5000]);
  }
}

$test = new RequestPresenterTest();
$test->run();
?>