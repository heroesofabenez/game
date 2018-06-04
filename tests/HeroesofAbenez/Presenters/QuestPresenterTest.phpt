<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;
use Nette\Application\BadRequestException;

final class QuestPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Quest:default");
    }, BadRequestException::class);
  }
  
  public function testView() {
    $this->checkAction("Quest:view", ["id" => 1]);
    $this->checkForward("Quest:view", "Quest:notfound", ["id" => 5000]);
  }
}

$test = new QuestPresenterTest();
$test->run();
?>