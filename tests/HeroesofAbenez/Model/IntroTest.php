<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class IntroTest extends \Tester\TestCase
{
    use TCharacterControl;

    private Intro $model;

    public function setUp(): void
    {
        $this->model = $this->getService(Intro::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetIntroPosition(): void
    {
        Assert::same(2, $this->model->getIntroPosition());
    }

    public function testGetIntroPart(): void
    {
        Assert::same("Part 1", $this->model->getIntroPart(1));
        Assert::same("", $this->model->getIntroPart(2));
    }

    public function testMoveToNextPart(): void
    {
        $this->preserveStats(["intro"], function () {
            $user = $this->getCharacter();
            $oldIntro = $user->intro;
            $this->model->moveToNextPart();
            Assert::same($oldIntro + 1, $user->intro);
        });
    }

    public function testGetStartingLocation(): void
    {
        \Tester\Environment::lock("database", __DIR__ . "/../../..");
        Assert::same(1, $this->model->getStartingLocation());
        $this->modifyCharacter(["race" => 1, "class" => 1], function () {
            Assert::same(4, $this->model->getStartingLocation());
        });
    }

    public function testEndIntro(): void
    {
        $this->modifyCharacter(["currentStage" => 2], function () {
            $this->model->endIntro();
            Assert::same(1, $this->getCharacterStat("currentStage")->id);
        });
    }
}

$test = new IntroTest();
$test->run();
