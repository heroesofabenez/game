<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\SkillAttack;
use HeroesofAbenez\Orm\SkillSpecial;
use HeroesofAbenez\Combat\CharacterAttackSkill;
use HeroesofAbenez\Combat\CharacterSpecialSkill;
use HeroesofAbenez\Combat\BaseCharacterSkill;

require __DIR__ . "/../../bootstrap.php";

final class SkillsTest extends \Tester\TestCase {
  /** @var Skills */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Skills::class);
  }
  
  public function testGetListOfAttackSkills() {
    $result = $this->model->getListOfAttackSkills();
    Assert::type("array", $result);
    Assert::type(SkillAttack::class, $result[1]);
  }
  
  public function testGetAttackSkill() {
    $skill = $this->model->getAttackSkill(1);
    Assert::type(SkillAttack::class, $skill);
  }
  
  public function testGetCharacterAttackSkill() {
    $skill = $this->model->getCharacterAttackSkill(1);
    Assert::type(CharacterAttackSkill::class, $skill);
    Assert::type("int", $skill->damage);
    Assert::type("int", $skill->hitRate);
    Assert::type("int", $skill->cooldown);
    Assert::same(0, $skill->cooldown);
    Assert::type("string", $skill->skillType);
    Assert::same("attack", $skill->skillType);
  }
  
  public function testGetListOfSpecialSkills() {
    $result = $this->model->getListOfSpecialSkills();
    Assert::type("array", $result);
    Assert::type(SkillSpecial::class, $result[1]);
  }
  
  public function testGetSpecialSkill() {
    $skill = $this->model->getSpecialSkill(1);
    Assert::type(SkillSpecial::class, $skill);
  }
  
  public function testGetCharacterSpecialSkill() {
    $skill = $this->model->getCharacterSpecialSkill(1);
    Assert::type(CharacterSpecialSkill::class, $skill);
    Assert::type("int", $skill->value);
    Assert::type("int", $skill->cooldown);
    Assert::same(0, $skill->cooldown);
    Assert::type("string", $skill->skillType);
    Assert::same("special", $skill->skillType);
  }
  
  public function testGetAvailableSkills() {
    $result = $this->model->getAvailableSkills();
    Assert::type("array", $result);
    Assert::type(BaseCharacterSkill::class, $result[0]);
  }
  
  public function testGetSkillPoints() {
    $result = $this->model->getSkillPoints();
    Assert::type("int", $result);
  }
}

$test = new SkillsTest();
$test->run();
?>