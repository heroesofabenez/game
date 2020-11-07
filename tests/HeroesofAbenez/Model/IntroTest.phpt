<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
final class IntroTest extends \Tester\TestCase {
  use TCharacterControl;

  /** @var Intro */
  protected $model;
  
  public function setUp() {
    $this->model = $this->getService(Intro::class);
  }

  public function testGetIntroPosition() {
    Assert::same(2, $this->model->getIntroPosition());
  }

  public function testGetIntroPart() {
    Assert::same("Part 1", $this->model->getIntroPart(1));
    Assert::same("", $this->model->getIntroPart(2));
  }

  public function testMoveToNextPart() {
    $this->preserveStats(["intro"], function() {
      $user = $this->getCharacter();
      $oldIntro = $user->intro;
      $this->model->moveToNextPart();
      Assert::same($oldIntro + 1, $user->intro);
    });
  }

  public function testGetStartingLocation() {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    Assert::same(1, $this->model->getStartingLocation());
    $this->modifyCharacter(["race" => 1, "class" => 1], function() {
      Assert::same(4, $this->model->getStartingLocation());
    });
  }

  public function testEndIntro() {
    $this->modifyCharacter(["currentStage" => 2], function() {
      $this->model->endIntro();
      Assert::same(1, $this->getCharacterStat("currentStage")->id);
    });
  }
}

$test = new IntroTest();
$test->run();
?>