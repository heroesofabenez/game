<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert,
    Nette\Application\BadRequestException;

final class SkillPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Skill:default");
    }, BadRequestException::class);
  }
  
  public function testAttack() {
    $this->checkAction("Skill:attack", ["id" => 1]);
    $this->checkForward("Skill:attack", "Skill:notfound", ["id" => 5000]);
  }
  
  public function testSpecial() {
    $this->checkAction("Skill:special", ["id" => 1]);
    $this->checkForward("Skill:special", "Skill:notfound", ["id" => 5000]);
  }
}

$test = new SkillPresenterTest;
$test->run();
?>