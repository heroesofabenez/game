<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\SkillAttackDummy,
    HeroesofAbenez\Orm\SkillSpecialDummy,
    HeroesofAbenez\Orm\CharacterAttackSkillDummy,
    HeroesofAbenez\Orm\CharacterSpecialSkillDummy,
    HeroesofAbenez\Orm\BaseCharacterSkill;

class SkillsTest extends MT\TestCase {
  /** @var Skills */
  protected $model;
  
  function __construct(Skills $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testGetListOfAttackSkills() {
    $result = $this->model->getListOfAttackSkills();
    Assert::type("array", $result);
    Assert::type(SkillAttackDummy::class, $result[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetAttackSkill(int $id) {
    $skill = $this->model->getAttackSkill($id);
    Assert::type(SkillAttackDummy::class, $skill);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetCharacterAttackSkill(int $id) {
    $skill = $this->model->getCharacterAttackSkill($id);
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
  function testGetListOfSpecialSkills() {
    $result = $this->model->getListOfSpecialSkills();
    Assert::type("array", $result);
    Assert::type(SkillSpecialDummy::class, $result[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetSpecialSkill(int $id) {
    $skill = $this->model->getSpecialSkill($id);
    Assert::type(SkillSpecialDummy::class, $skill);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetCharacterSpecialSkill(int $id) {
    $skill = $this->model->getCharacterSpecialSkill($id);
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
  function testGetAvailableSkills() {
    $result = $this->model->getAvailableSkills();
    Assert::type("array", $result);
    Assert::type(BaseCharacterSkill::class, $result[0]);
  }
  
  /**
   * @return void
   */
  function testGetSkillPoints() {
    $result = $this->model->getSkillPoints();
    Assert::type("int", $result);
  }
}
?>