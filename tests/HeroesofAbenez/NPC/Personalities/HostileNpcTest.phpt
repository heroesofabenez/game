<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../../bootstrap.php";

use HeroesofAbenez\Orm\Npc;
use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class HostileNpcTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;

  private HostileNpc $personality;
  private \Nette\Security\User $user;
  
  protected function setUp() {
    $this->personality = $this->getService(HostileNpc::class);
    $this->user = $this->getService(\Nette\Security\User::class);
  }
  
  public function testGetName() {
    Assert::same(Npc::PERSONALITY_HOSTILE, $this->personality->getName());
  }

  public function testGetMood() {
    $identity = clone $this->user->identity;
    $npc = new Npc();
    Assert::same($this->personality->getName(), $this->personality->getMood($identity, $npc));
  }
}

$test = new HostileNpcTest();
$test->run();
?>