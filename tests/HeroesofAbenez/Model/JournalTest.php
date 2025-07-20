<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Character;
use HeroesofAbenez\Orm\CharacterQuest;
use HeroesofAbenez\Orm\PetType;
use HeroesofAbenez\Utils\Karma;
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
  
  public function setUp(): void {
    $this->model = $this->getService(Journal::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testBasic(): void {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    $result = $this->model->basic();
    Assert::type("array", $result);
    Assert::same("James The Invisible", $result["name"]);
    Assert::same(Character::GENDER_MALE, $result["gender"]);
    Assert::same(2, $result["race"]);
    Assert::same(3, $result["class"]);
    Assert::null($result["specialization"]);
    Assert::same(3, $result["level"]);
    Assert::same(0, $result["whiteKarma"]);
    Assert::same(0, $result["darkKarma"]);
    Assert::same(143, $result["experiences"]);
    Assert::same("Study Room", $result["stageName"]);
    Assert::same("Academy of Magic", $result["areaName"]);
    Assert::same(Karma::KARMA_NEUTRAL, $result["predominantKarma"]);
    Assert::same("Dawn", $result["guild"]);
    Assert::same(7, $result["guildRank"]);
  }
  
  public function testInventory(): void {
    $inventory = $this->model->inventory();
    Assert::type("array", $inventory);
    Assert::count(2, $inventory);
    Assert::same(159, $inventory["money"]);
    Assert::count(1, $inventory["items"]);
    $item = $inventory["items"][0];
    Assert::type(\stdClass::class, $item);
    Assert::same(6, $item->id);
    Assert::same("Apprentice's Wand", $item->name);
    Assert::same(1, $item->amount);
    Assert::true($item->worn);
    Assert::same(1, $item->eqid);
    Assert::same(10, $item->durability);
    Assert::same(10, $item->maxDurability);
    Assert::same(0, $item->repairPrice);
    Assert::true($item->equipable);
  }
  
  public function testPets(): void {
    $pets = $this->model->pets();
    Assert::type(ICollection::class, $pets);
    Assert::count(1, $pets);
    /** @var PetEntity $pet */
    $pet = $pets->fetch();
    Assert::type(PetEntity::class, $pet);
    Assert::same(1, $pet->id);
    Assert::null($pet->name);
    Assert::true($pet->deployed);
    Assert::same(PetType::STAT_INT, $pet->bonusStat);
    Assert::same(5, $pet->bonusValue);
  }
  
  public function testCurrentQuests(): void {
    $quests = $this->model->currentQuests();
    Assert::type(ICollection::class, $quests);
    Assert::count(1, $quests);
    /** @var CharacterQuest $quest */
    $quest = $quests->fetch();
    Assert::type(CharacterQuest::class, $quest);
    Assert::same(1, $quest->id);
    Assert::same(CharacterQuest::PROGRESS_STARTED, $quest->progress);
  }

  public function testFinishedQuests(): void {
    $quests = $this->model->finishedQuests();
    Assert::type(ICollection::class, $quests);
    Assert::count(0, $quests);
  }

  public function testFriends(): void {
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