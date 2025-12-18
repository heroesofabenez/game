<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class NpcPersonalityChooserTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    private NpcPersonalityChooser $model;

    public function setUp(): void
    {
        $this->model = $this->getService(NpcPersonalityChooser::class); // @phpstan-ignore assign.propertyType
    }

    public function testGetPersonality(): void
    {
        $npc = new \HeroesofAbenez\Orm\Npc();
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_CRAZY;
        Assert::type(Personalities\CrazyNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_ELITIST;
        Assert::type(Personalities\ElitistNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_FRIENDLY;
        Assert::type(Personalities\FriendlyNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_HOSTILE;
        Assert::type(Personalities\HostileNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_MISOGYNIST;
        Assert::type(Personalities\MisogynistNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_RACIST;
        Assert::type(Personalities\RacistNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_RESERVED;
        Assert::type(Personalities\ReservedNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_SHY;
        Assert::type(Personalities\ShyNpc::class, $this->model->getPersonality($npc));
        $npc->personality = \HeroesofAbenez\Orm\Npc::PERSONALITY_TEACHING;
        Assert::type(Personalities\TeachingNpc::class, $this->model->getPersonality($npc));
        $model = new NpcPersonalityChooser([]);
        $personality = $model->getPersonality($npc);
        Assert::false(get_class($personality) === Personalities\TeachingNpc::class);
        Assert::same($npc->personality, $personality->getName());
    }
}

$test = new NpcPersonalityChooserTest();
$test->run();
