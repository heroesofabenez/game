<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;
use Nette\Application\BadRequestException;

/**
 * @author Jakub Konečný
 * @skip
 */
final class QuestPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault(): void {
    Assert::exception(function() {
      $this->checkAction("Quest:default");
    }, BadRequestException::class);
  }
  
  public function testView(): void {
    $this->checkAction("Quest:view", ["id" => 1]);
    Assert::exception(function() {
      $this->checkAction("Quest:view", ["id" => 5000]);
    }, BadRequestException::class);
  }
}

$test = new QuestPresenterTest();
$test->run();
?>