<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Nette\Application\BadRequestException;
use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @skip
 */
final class ProfilePresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault(): void {
    $this->checkForward("Profile:default", "Profile:view");
  }
  
  public function testView(): void {
    Assert::exception(function() {
      $this->checkAction("Profile:view", ["id" => 5000]);
    }, BadRequestException::class);
    $this->checkAction("Profile:view", ["id" => 1]);
  }
}

$test = new ProfilePresenterTest();
$test->run();
?>