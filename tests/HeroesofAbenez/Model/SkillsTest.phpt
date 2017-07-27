<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\SkillAttackDummy,
    HeroesofAbenez\Orm\SkillSpecialDummy,
    HeroesofAbenez\Orm\CharacterAttackSkillDummy,
    HeroesofAbenez\Orm\CharacterSpecialSkillDummy,
    HeroesofAbenez\Orm\BaseCharacterSkill;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class SkillsTest extends \Tester\TestCase {
  /** @var Skills */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Skills::class);
  }
  
  /**
   * @return void
   */
  public function testGetListOfAttackSkills() {
    $result = $this->model->getListOfAttackSkills();
    Assert::type("array", $result);
    Assert::type(SkillAttackDummy::class, $result[1]);
  }
  
  /**
   * @return void
   */
  public function testGetAttackSkill() {
    $skill = $this->model->getAttackSkill(1);
    Assert::type(SkillAttackDummy::class, $skill);
  }
  
  /**
   * @return void
   */
  public function testGetCharacterAttackSkill() {
    $skill = $this->model->getCharacterAttackSkill(1);
    Assert::type(CharacterAttackSkillDummy::class, $skill);
    Assert::type("int", $skill->damage);
    Assert::type("int", $skill->hitRate);
    Assert::type("int", $skill->cooldown);
    Assert::same(0, $skill->cooldown);
    Assert::type("string", $skill->skillType);
    Assert::same("attack", $skill->skillType);
  }
  
  /**
   * @return void
   */
  public function testGetListOfSpecialSkills() {
    $result = $this->model->getListOfSpecialSkills();
    Assert::type("array", $result);
    Assert::type(SkillSpecialDummy::class, $result[1]);
  }
  
  /**
   * @return void
   */
  public function testGetSpecialSkill() {
    $skill = $this->model->getSpecialSkill(1);
    Assert::type(SkillSpecialDummy::class, $skill);
  }
  
  /**
   * @return void
   */
  public function testGetCharacterSpecialSkill() {
    $skill = $this->model->getCharacterSpecialSkill(1);
    Assert::type(CharacterSpecialSkillDummy::class, $skill);
    Assert::type("int", $skill->value);
    Assert::type("int", $skill->cooldown);
    Assert::same(0, $skill->cooldown);
    Assert::type("string", $skill->skillType);
    Assert::same("special", $skill->skillType);
  }
  
  /**
   * @return void
   */
  public function testGetAvailableSkills() {
    $result = $this->model->getAvailableSkills();
    Assert::type("array", $result);
    Assert::type(BaseCharacterSkill::class, $result[0]);
  }
  
  /**
   * @return void
   */
  public function testGetSkillPoints() {
    $result = $this->model->getSkillPoints();
    Assert::type("int", $result);
  }
}

$test = new SkillsTest;
$test->run();
?>