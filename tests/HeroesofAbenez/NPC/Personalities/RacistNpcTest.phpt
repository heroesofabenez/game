<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../../bootstrap.php";

use HeroesofAbenez\Orm\Npc;
use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class RacistNpcTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var RacistNpc */
  protected $personality;
  /** @var \Nette\Security\User */
  protected $user;
  
  protected function setUp() {
    $this->personality = $this->getService(RacistNpc::class);
    $this->user = $this->getService(\Nette\Security\User::class);
  }
  
  public function testGetName() {
    Assert::same(Npc::PERSONALITY_RACIST, $this->personality->getName());
  }

  public function testGetMood() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $identity = clone $this->user->identity;
    $npc = new Npc();
    $identity->race = 0;
    $npc->race = $orm->races->getById(1);
    Assert::same(Npc::PERSONALITY_HOSTILE, $this->personality->getMood($identity, $npc));
    $identity->race = $npc->race->id;
    Assert::same(Npc::PERSONALITY_CRAZY, $this->personality->getMood($identity, $npc));
  }
}

$test = new RacistNpcTest();
$test->run();
?>