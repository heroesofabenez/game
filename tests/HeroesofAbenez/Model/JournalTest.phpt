<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\Pet as PetEntity;

require __DIR__ . "/../../bootstrap.php";

final class JournalTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var Journal */
  protected $model;
  
  public function setUp() {
    $this->model = $this->getService(Journal::class);
  }
  
  public function testBasic() {
    $result = $this->model->basic();
    Assert::type("array", $result);
  }
  
  public function testInventory() {
    $inventory = $this->model->inventory();
    Assert::type("array", $inventory);
    Assert::count(3, $inventory);
    Assert::type("int", $inventory["money"]);
    Assert::count(0, $inventory["items"]);
    Assert::type("array", $inventory["equipments"]);
    Assert::count(1, $inventory["equipments"]);
    Assert::type(\stdClass::class, $inventory["equipments"][0]);
  }
  
  public function testPets() {
    $pets = $this->model->pets();
    Assert::type(ICollection::class, $pets);
    Assert::count(1, $pets);
    Assert::type(PetEntity::class, $pets->fetch());
  }
  
  public function testQuests() {
    $quests = $this->model->quests();
    Assert::type("array", $quests);
    Assert::count(1, $quests);
    Assert::type("int", $quests[0]);
  }
}

$test = new JournalTest();
$test->run();
?>