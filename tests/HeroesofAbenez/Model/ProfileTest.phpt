<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

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
  }
  
  public function testGetClassesList() {
    $list = $this->model->getClassesList();
    Assert::type(ICollection::class, $list);
    Assert::count(4, $list);
  }
  
  public function testView() {
    Assert::null($this->model->view(5000));
    $result = $this->model->view(1);
    Assert::type("array", $result);
    Assert::count(18, $result);
    Assert::same("male", $result["gender"]);
    Assert::type("int", $result["guild"]);
    Assert::null($result["specialization"]);
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
  
  public function testGetStats() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->getStats();
    Assert::type("array", $result);
    Assert::count(5, $result);
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