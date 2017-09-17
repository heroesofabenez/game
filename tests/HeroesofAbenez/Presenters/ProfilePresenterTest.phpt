<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

class ProfilePresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    $this->checkForward("Profile:default", "Profile:view");
  }
  
  public function testView() {
    $this->checkForward("Profile:view", "Profile:notfound", ["id" => 5000]);
    $this->checkAction("Profile:view", ["id" => 1]);
  }
}

$test = new ProfilePresenterTest;
$test->run();
?>