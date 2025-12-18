<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @skip
 */
final class ArenaPresenterTest extends \Tester\TestCase
{
    use TPresenter;

    public function testDefault(): void
    {
        $this->checkAction("Arena:default");
    }

    public function testHeroes(): void
    {
        $this->checkAction("Arena:heroes");
    }

    public function testChampion(): void
    {
        $this->checkAction("Arena:champion", ["id" => 1]);
    }
}

$test = new ArenaPresenterTest();
$test->run();
