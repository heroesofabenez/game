<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\BadRequestException;
use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

final class PostofficePresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testReceived() {
    $this->checkAction("Postoffice:received");
  }
  
  public function testSent() {
    $this->checkAction("Postoffice:sent");
  }
  
  public function testNew() {
    $this->checkAction("Postoffice:new");
  }
  
  public function testMessage() {
    Assert::exception(function() {
      $this->checkAction("Postoffice:message", ["id" => 5000]);
    }, BadRequestException::class);
    $this->checkAction("Postoffice:message", ["id" => 1]);
  }
}

$test = new PostofficePresenterTest();
$test->run();
?>