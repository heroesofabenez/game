<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

class PostofficePresenterTest extends \Tester\TestCase {
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
    $this->checkForward("Postoffice:message", "Postoffice:notfound", ["id" => 5000]);
    $this->checkAction("Postoffice:message", ["id" => 1]);
  }
}

$test = new PostofficePresenterTest;
$test->run();
?>