<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @skip
 */
final class RankingPresenterTest extends \Tester\TestCase
{
    use TPresenter;

    public function testCharacters(): void
    {
        $this->checkAction("Ranking:characters");
    }

    public function testGuilds(): void
    {
        $this->checkAction("Ranking:guilds");
    }
}

$test = new RankingPresenterTest();
$test->run();
