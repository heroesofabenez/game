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
final class SkillsTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    private Skills $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Skills::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetListOfAttackSkills(): void
    {
        $result = $this->model->getListOfAttackSkills();
        Assert::type("array", $result);
        Assert::count(18, $result);
        foreach ($result as $skill) {
            Assert::type(SkillAttack::class, $skill);
        }
    }

    public function testGetAttackSkill(): void
    {
        $skill = $this->model->getAttackSkill(1);
        Assert::type(SkillAttack::class, $skill);
        Assert::null($this->model->getAttackSkill(5000));
    }

    public function testGetCharacterAttackSkill(): void
    {
        /** @var CharacterAttackSkill $skill */
        $skill = $this->model->getCharacterAttackSkill(3);
        Assert::type(CharacterAttackSkill::class, $skill);
        Assert::same(2, $skill->level);
        /** @var CharacterAttackSkill $skill */
        $skill = $this->model->getCharacterAttackSkill(1);
        Assert::type(CharacterAttackSkill::class, $skill);
        Assert::same(0, $skill->level);
        Assert::null($this->model->getCharacterAttackSkill(5000));
    }

    public function testGetListOfSpecialSkills(): void
    {
        $result = $this->model->getListOfSpecialSkills();
        Assert::type("array", $result);
        Assert::count(24, $result);
        foreach ($result as $skill) {
            Assert::type(SkillSpecial::class, $skill);
        }
    }

    public function testGetSpecialSkill(): void
    {
        $skill = $this->model->getSpecialSkill(1);
        Assert::type(SkillSpecial::class, $skill);
        Assert::null($this->model->getSpecialSkill(5000));
    }

    public function testGetCharacterSpecialSkill(): void
    {
        /** @var CharacterSpecialSkill $skill */
        $skill = $this->model->getCharacterSpecialSkill(1);
        Assert::type(CharacterSpecialSkill::class, $skill);
        Assert::same(0, $skill->level);
        Assert::null($this->model->getCharacterSpecialSkill(5000));
    }

    public function testGetAvailableSkills(): void
    {
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

    public function testGetSkillPoints(): void
    {
        $result = $this->model->getSkillPoints();
        Assert::same(0, $result);
    }

    public function testTrainSkill(): void
    {
        Assert::exception(function () {
            $this->model->trainSkill(5000, "abc");
        }, InvalidSkillTypeException::class);
        Assert::exception(function () {
            $this->model->trainSkill(5000, "attack");
        }, NoSkillPointsAvailableException::class);
    }
}

$test = new SkillsTest();
$test->run();
