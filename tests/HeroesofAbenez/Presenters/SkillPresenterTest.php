<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;
use Nette\Application\BadRequestException;

/**
 * @author Jakub Konečný
 * @skip
 */
final class SkillPresenterTest extends \Tester\TestCase
{
    use TPresenter;

    public function testDefault(): void
    {
        Assert::exception(function () {
            $this->checkAction("Skill:default");
        }, BadRequestException::class);
    }

    public function testAttack(): void
    {
        $this->checkAction("Skill:attack", ["id" => 1]);
        Assert::exception(function () {
            $this->checkAction("Skill:attack", ["id" => 5000]);
        }, BadRequestException::class);
    }

    public function testSpecial(): void
    {
        $this->checkAction("Skill:special", ["id" => 1]);
        Assert::exception(function () {
            $this->checkAction("Skill:special", ["id" => 5000]);
        }, BadRequestException::class);
    }
}

$test = new SkillPresenterTest();
$test->run();
