<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\SkillAttack;
use HeroesofAbenez\Orm\SkillSpecial;
use HeroesofAbenez\Orm\CharacterAttackSkill;
use HeroesofAbenez\Orm\CharacterSpecialSkill;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class SkillsTest extends \Tester\TestCase {
  private Skills $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Skills::class);
  }
  
  public function testGetListOfAttackSkills() {
    $result = $this->model->getListOfAttackSkills();
    Assert::type("array", $result);
    Assert::count(18, $result);
    foreach($result as $skill) {
      Assert::type(SkillAttack::class, $skill);
    }
  }
  
  public function testGetAttackSkill() {
    $skill = $this->model->getAttackSkill(1);
    Assert::type(SkillAttack::class, $skill);
    Assert::null($this->model->getAttackSkill(5000));
  }
  
  public function testGetCharacterAttackSkill() {
    $skill = $this->model->getCharacterAttackSkill(3);
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::same(2, $skill->level);
    $skill = $this->model->getCharacterAttackSkill(1);
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::same(0, $skill->level);
    Assert::null($this->model->getCharacterAttackSkill(5000));
  }
  
  public function testGetListOfSpecialSkills() {
    $result = $this->model->getListOfSpecialSkills();
    Assert::type("array", $result);
    Assert::count(24, $result);
    foreach($result as $skill) {
      Assert::type(SkillSpecial::class, $skill);
    }
  }
  
  public function testGetSpecialSkill() {
    $skill = $this->model->getSpecialSkill(1);
    Assert::type(SkillSpecial::class, $skill);
    Assert::null($this->model->getSpecialSkill(5000));
  }

  public function testGetCharacterSpecialSkill() {
    $skill = $this->model->getCharacterSpecialSkill(1);
    Assert::type(CharacterSpecialSkill::class, $skill);
    Assert::same(0, $skill->level);
    Assert::null($this->model->getCharacterSpecialSkill(5000));
  }
  
  public function testGetAvailableSkills() {
    $result = $this->model->getAvailableSkills();
    Assert::type("array", $result);
    Assert::count(2, $result);
    Assert::type(CharacterAttackSkill::class, $result[0]);
    Assert::same(3, $result[0]->skill->id);
    Assert::same(2, $result[0]->level);
    Assert::type(CharacterSpecialSkill::class, $result[1]);
    Assert::same(3, $result[1]->skill->id);
    Assert::same(0, $result[1]->level);
  }
  
  public function testGetSkillPoints() {
    $result = $this->model->getSkillPoints();
    Assert::same(0, $result);
  }

  public function testTrainSkill() {
    Assert::exception(function() {
      $this->model->trainSkill(5000, "abc");
    }, InvalidSkillTypeException::class);
    Assert::exception(function() {
      $this->model->trainSkill(5000, "attack");
    }, NoSkillPointsAvailableException::class);
  }
}

$test = new SkillsTest();
$test->run();
?>