<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Character;
use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Utils\Karma;
use Tester\Assert;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class ProfileTest extends \Tester\TestCase {
  use TCharacterControl;

  private Profile $model;

  public function setUp() {
    $this->model = $this->getService(Profile::class);
  }
  
  public function testGetRacesList() {
    $list = $this->model->getRacesList();
    Assert::type(ICollection::class, $list);
    Assert::count(4, $list);
    /** @var CharacterRace $race */
    $race = $list->fetch();
    Assert::same(1, $race->id);
    Assert::same("barbarian", $race->name);
    Assert::same(11, $race->strength);
    Assert::same(10, $race->dexterity);
    Assert::same(11, $race->constitution);
    Assert::same(9, $race->intelligence);
    Assert::same(9, $race->charisma);
    Assert::true($race->playable);
    /** @var CharacterRace $race */
    $race = $list->fetch();
    Assert::same(2, $race->id);
    Assert::same("human", $race->name);
    Assert::same(10, $race->strength);
    Assert::same(10, $race->dexterity);
    Assert::same(10, $race->constitution);
    Assert::same(10, $race->intelligence);
    Assert::same(10, $race->charisma);
    Assert::true($race->playable);
    /** @var CharacterRace $race */
    $race = $list->fetch();
    Assert::same(3, $race->id);
    Assert::same("elf", $race->name);
    Assert::same(9, $race->strength);
    Assert::same(11, $race->dexterity);
    Assert::same(8, $race->constitution);
    Assert::same(11, $race->intelligence);
    Assert::same(12, $race->charisma);
    Assert::true($race->playable);
    /** @var CharacterRace $race */
    $race = $list->fetch();
    Assert::same(4, $race->id);
    Assert::same("dwarf", $race->name);
    Assert::same(11, $race->strength);
    Assert::same(9, $race->dexterity);
    Assert::same(12, $race->constitution);
    Assert::same(9, $race->intelligence);
    Assert::same(9, $race->charisma);
    Assert::true($race->playable);
  }
  
  public function testGetClassesList() {
    $list = $this->model->getClassesList();
    Assert::type(ICollection::class, $list);
    Assert::count(4, $list);
    /** @var CharacterClass $class */
    $class = $list->fetch();
    Assert::same(1, $class->id);
    Assert::same("fighter", $class->name);
    Assert::same(1, $class->strength);
    Assert::same(0.25, $class->strengthGrow);
    Assert::same(0, $class->dexterity);
    Assert::same(0.2, $class->dexterityGrow);
    Assert::same(2, $class->constitution);
    Assert::same(0.5, $class->constitutionGrow);
    Assert::same(-1, $class->intelligence);
    Assert::same(0.0, $class->intelligenceGrow);
    Assert::same(-1, $class->charisma);
    Assert::same(0.1, $class->charismaGrow);
    Assert::same(1.0, $class->statPointsLevel);
    Assert::same("1d5+DEX/4", $class->initiative);
    Assert::true($class->playable);
    Assert::same("constitution", $class->mainStat);
    /** @var CharacterClass $class */
    $class = $list->fetch();
    Assert::same(2, $class->id);
    Assert::same("rogue", $class->name);
    Assert::same(1, $class->strength);
    Assert::same(0.25, $class->strengthGrow);
    Assert::same(2, $class->dexterity);
    Assert::same(0.5, $class->dexterityGrow);
    Assert::same(-2, $class->constitution);
    Assert::same(0.0, $class->constitutionGrow);
    Assert::same(0, $class->intelligence);
    Assert::same(0.1, $class->intelligenceGrow);
    Assert::same(0, $class->charisma);
    Assert::same(0.2, $class->charismaGrow);
    Assert::same(1.0, $class->statPointsLevel);
    Assert::same("2d3+DEX/4", $class->initiative);
    Assert::true($class->playable);
    Assert::same("dexterity", $class->mainStat);
    /** @var CharacterClass $class */
    $class = $list->fetch();
    Assert::same(3, $class->id);
    Assert::same("wizard", $class->name);
    Assert::same(-1, $class->strength);
    Assert::same(0.0, $class->strengthGrow);
    Assert::same(0, $class->dexterity);
    Assert::same(0.1, $class->dexterityGrow);
    Assert::same(-1, $class->constitution);
    Assert::same(0.1, $class->constitutionGrow);
    Assert::same(2, $class->intelligence);
    Assert::same(0.5, $class->intelligenceGrow);
    Assert::same(1, $class->charisma);
    Assert::same(0.25, $class->charismaGrow);
    Assert::same(1.1, $class->statPointsLevel);
    Assert::same("5d2+INT/3", $class->initiative);
    Assert::true($class->playable);
    Assert::same("intelligence", $class->mainStat);
    /** @var CharacterClass $class */
    $class = $list->fetch();
    Assert::same(4, $class->id);
    Assert::same("archer", $class->name);
    Assert::same(-1, $class->strength);
    Assert::same(0.0, $class->strengthGrow);
    Assert::same(2, $class->dexterity);
    Assert::same(0.5, $class->dexterityGrow);
    Assert::same(0, $class->constitution);
    Assert::same(0.1, $class->constitutionGrow);
    Assert::same(0, $class->intelligence);
    Assert::same(0.25, $class->intelligenceGrow);
    Assert::same(0, $class->charisma);
    Assert::same(0.1, $class->charismaGrow);
    Assert::same(1.1, $class->statPointsLevel);
    Assert::same("4d2+DEX/4", $class->initiative);
    Assert::true($class->playable);
    Assert::same("dexterity", $class->mainStat);
  }
  
  public function testView() {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    Assert::null($this->model->view(5000));
    $result = $this->model->view(1);
    Assert::type("array", $result);
    Assert::same(1, $result["id"]);
    Assert::same("James The Invisible", $result["name"]);
    Assert::same(Character::GENDER_MALE, $result["gender"]);
    Assert::same(2, $result["race"]);
    Assert::same(9.0, $result["strength"]);
    Assert::same(10.2, $result["dexterity"]);
    Assert::same(10.2, $result["constitution"]);
    Assert::same(14.0, $result["intelligence"]);
    Assert::same(11.5, $result["charisma"]);
    Assert::same(3, $result["class"]);
    Assert::null($result["specialization"]);
    Assert::same(Karma::KARMA_NEUTRAL, $result["predominantKarma"]);
    Assert::same(1, $result["guild"]);
    Assert::same(7, $result["guildrank"]);
    Assert::same("Study Room", $result["stage"]);
    Assert::same("Academy of Magic", $result["area"]);
    Assert::type(\HeroesofAbenez\Orm\Pet::class, $result["pet"]);
  }

  public function testGetAvailableSpecializations() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $user = $this->getCharacter();
    $oldLevel = $user->level;
    $oldSpecialization = $user->specialization;
    Assert::same([], $this->model->getAvailableSpecializations());
    $user->level = CharacterBuilder::SPECIALIZATION_LEVEL - 1;
    Assert::same([7, 8], $this->model->getAvailableSpecializations());
    $user->specialization = 7;
    Assert::same([], $this->model->getAvailableSpecializations());
    $user->level = $oldLevel;
    $user->specialization = $oldSpecialization;
    $orm->characters->persistAndFlush($user);
  }

  public function testGetStatPoints() {
    Assert::same(0, $this->model->getStatPoints());
  }

  public function testGetCharismaBonus() {
    Assert::same(3, $this->model->getCharismaBonus());
  }
  
  public function testGetStats() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
    Assert::same(9.0, $result["strength"]);
    Assert::same(10.2, $result["dexterity"]);
    Assert::same(10.2, $result["constitution"]);
    Assert::same(14.0, $result["intelligence"]);
    Assert::same(11.5, $result["charisma"]);
  }

  public function testLevelUp() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    Assert::exception(function() {
      $this->model->levelUp();
    }, NotEnoughExperiencesException::class);
    $statsToPreserve = [
      "experience", "level", "statPoints", "skillPoints", "strength", "dexterity", "constitution",
      "intelligence", "charisma",
    ];
    $this->preserveStats($statsToPreserve, function() {
      $user = $this->getCharacter();
      $user->experience = $this->model->getLevelsRequirements()[$user->level + 1];
      /** @var \HeroesofAbenez\Orm\Model $orm */
      $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
      $orm->characters->persistAndFlush($user);
      Assert::exception(function() {
        $this->model->levelUp(1);
      }, CannotChooseSpecializationException::class);
      $user->level = CharacterBuilder::SPECIALIZATION_LEVEL;
      $user->experience = $this->model->getLevelsRequirements()[$user->level + 1];
      $orm->characters->persistAndFlush($user);
      Assert::exception(function() {
        $this->model->levelUp();
      }, SpecializationNotChosenException::class);
      Assert::exception(function() {
        $this->model->levelUp(1);
      }, SpecializationNotAvailableException::class);
    });
    $this->preserveStats($statsToPreserve, function() {
      $user = $this->getCharacter();
      $oldLevel = $user->level;
      $oldStatPoints = $user->statPoints;
      $oldSkillPoints = $user->skillPoints;
      $oldIntelligence = $user->intelligence;
      $user->experience = $this->model->getLevelsRequirements()[$user->level + 1];
      /** @var \HeroesofAbenez\Orm\Model $orm */
      $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
      $orm->characters->persistAndFlush($user);
      $this->model->levelUp();
      Assert::same($oldLevel + 1, $user->level);
      Assert::same($oldStatPoints + $user->class->statPointsLevel, $user->statPoints);
      Assert::same($oldSkillPoints + 1, $user->skillPoints);
      Assert::same($oldIntelligence + $user->class->intelligenceGrow, $user->intelligence);
    });
  }

  public function testTrainStat() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    Assert::exception(function() {
      $this->model->trainStat("abc");
    }, InvalidStatException::class);
    Assert::exception(function() {
      $this->model->trainStat("charisma");
    }, NoStatPointsAvailableException::class);
    $charisma = $this->getCharacterStat("charisma");
    $this->modifyCharacter(["statPoints" => 1, "charisma" => $charisma, ], function() use($charisma) {
      $this->model->trainStat("charisma");
      $user = $this->getCharacter();
      Assert::same($charisma + 1, $user->charisma);
      Assert::same(0, (int) $user->statPoints);
    });
  }
}

$test = new ProfileTest();
$test->run();
?>