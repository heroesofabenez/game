<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../../bootstrap.php";

use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Utils\Karma;
use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class CrazyNpcTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var CrazyNpc */
  protected $personality;
  /** @var \Nette\Security\User */
  protected $user;
  
  protected function setUp() {
    $this->personality = $this->getService(CrazyNpc::class);
    $this->user = $this->getService(\Nette\Security\User::class);
  }
  
  public function testGetName() {
    Assert::same(Npc::PERSONALITY_CRAZY, $this->personality->getName());
  }

  public function testGetMood() {
    $identity = clone $this->user->identity;
    $npc = new Npc();
    $identity->white_karma = 50;
    $identity->dark_karma = 0;
    $npc->karma = Karma::KARMA_WHITE;
    Assert::same($this->personality->getName(), $this->personality->getMood($identity, $npc));
    $npc->karma = Karma::KARMA_NEUTRAL;
    Assert::same($this->personality->getName(), $this->personality->getMood($identity, $npc));
    $npc->karma = Karma::KARMA_DARK;
    Assert::same(Npc::PERSONALITY_HOSTILE, $this->personality->getMood($identity, $npc));
  }
}

$test = new CrazyNpcTest();
$test->run();
?>