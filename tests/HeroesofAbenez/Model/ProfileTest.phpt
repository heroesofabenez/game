<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

final class ProfileTest extends \Tester\TestCase {
  /** @var Profile */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Profile::class);
  }
  
  public function testGetRacesList() {
    $list = $this->model->getRacesList();
    Assert::type(ICollection::class, $list);
  }
  
  public function testGetClassesList() {
    $list = $this->model->getClassesList();
    Assert::type(ICollection::class, $list);
  }
  
  public function testView() {
    Assert::null($this->model->view(5000));
    $result = $this->model->view(1);
    Assert::type("array", $result);
    Assert::count(19, $result);
    Assert::same("male", $result["gender"]);
    Assert::type("int", $result["guild"]);
    Assert::null($result["specialization"]);
    Assert::type(\HeroesofAbenez\Orm\Pet::class, $result["pet"]);
  }

  public function testGetAvailableSpecializations() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var \HeroesofAbenez\Orm\Character $user */
    $user = $orm->characters->getById(1);
    $oldLevel = $user->level;
    $oldSpecialization = $user->specialization;
    Assert::same([], $this->model->getAvailableSpecializations());
    $user->level = Profile::SPECIALIZATION_LEVEL - 1;
    Assert::same([8, 9], $this->model->getAvailableSpecializations());
    $user->specialization = 8;
    Assert::same([], $this->model->getAvailableSpecializations());
    $user->level = $oldLevel;
    $user->specialization = $oldSpecialization;
    $orm->characters->persistAndFlush($user);
  }
  
  public function testGetStats() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
  }

  public function testTrainStat() {
    Assert::exception(function() {
      $this->model->trainStat("abc");
    }, InvalidStatException::class);
    Assert::exception(function() {
      $this->model->trainStat("charisma");
    }, NoStatPointsAvailableException::class);
  }
}

$test = new ProfileTest();
$test->run();
?>