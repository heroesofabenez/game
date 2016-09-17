<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\SkillAttack,
    HeroesofAbenez\Entities\SkillSpecial,
    HeroesofAbenez\Entities\CharacterSkillAttack,
    HeroesofAbenez\Entities\CharacterSkillSpecial,
    HeroesofAbenez\Entities\CharacterSkill;

class SkillsModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Skills */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Skills $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testGetListOfAttackSkills() {
    $result = $this->model->getListOfAttackSkills();
    Assert::type("array", $result);
    Assert::type(SkillAttack::class, $result[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetAttackSkill($id) {
    $skill = $this->model->getAttackSkill($id);
    Assert::type(SkillAttack::class, $skill);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetCharacterAttackSkill($id) {
    $skill = $this->model->getCharacterAttackSkill($id);
    Assert::type(CharacterSkillAttack::class, $skill);
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
    Assert::type(SkillSpecial::class, $result[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetSpecialSkill($id) {
    $skill = $this->model->getSpecialSkill($id);
    Assert::type(SkillSpecial::class, $skill);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetCharacterSpecialSkill($id) {
    $skill = $this->model->getCharacterSpecialSkill($id);
    Assert::type(CharacterSkillSpecial::class, $skill);
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
    Assert::type(CharacterSkill::class, $result[0]);
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