<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;
use Nette\Application\BadRequestException;

/**
 * @author Jakub Konečný
 * @testCase
 */
final class SkillPresenterTest extends \Tester\TestCase {
  use TPresenter;
  
  public function testDefault() {
    Assert::exception(function() {
      $this->checkAction("Skill:default");
    }, BadRequestException::class);
  }
  
  public function testAttack() {
    $this->checkAction("Skill:attack", ["id" => 1]);
    Assert::exception(function() {
      $this->checkAction("Skill:attack", ["id" => 5000]);
    }, BadRequestException::class);
  }
  
  public function testSpecial() {
    $this->checkAction("Skill:special", ["id" => 1]);
    Assert::exception(function() {
      $this->checkAction("Skill:special", ["id" => 5000]);
    }, BadRequestException::class);
  }
}

$test = new SkillPresenterTest();
$test->run();
?>