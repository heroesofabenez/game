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
    Assert::count(17, $result);
    Assert::same("male", $result["gender"]);
    Assert::type("int", $result["guild"]);
    Assert::null($result["specialization"]);
    Assert::type(\HeroesofAbenez\Orm\Pet::class, $result["pet"]);
  }
  
  public function testGetStats() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
  }
}

$test = new ProfileTest();
$test->run();
?>