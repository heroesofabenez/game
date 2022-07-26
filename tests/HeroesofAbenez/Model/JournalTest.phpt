<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\Pet as PetEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class JournalTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;

  private Journal $model;
  
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
    Assert::count(2, $inventory);
    Assert::type("int", $inventory["money"]);
    Assert::count(1, $inventory["items"]);
    Assert::type(\stdClass::class, $inventory["items"][0]);
  }
  
  public function testPets() {
    $pets = $this->model->pets();
    Assert::type(ICollection::class, $pets);
    Assert::count(1, $pets);
    Assert::type(PetEntity::class, $pets->fetch());
  }
  
  public function testCurrentQuests() {
    $quests = $this->model->currentQuests();
    Assert::type(ICollection::class, $quests);
    Assert::count(1, $quests);
  }

  public function testFinishedQuests() {
    $quests = $this->model->currentQuests();
    Assert::type(ICollection::class, $quests);
  }

  public function testFriends() {
    $friends = $this->model->friends();
    Assert::type("array", $friends);
    Assert::count(2, $friends);
    Assert::type(\HeroesofAbenez\Orm\Character::class, $friends[0]);
    Assert::same(2, $friends[0]->id);
    Assert::type(\HeroesofAbenez\Orm\Character::class, $friends[1]);
    Assert::same(3, $friends[1]->id);
  }
}

$test = new JournalTest();
$test->run();
?>