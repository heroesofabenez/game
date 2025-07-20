<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../../bootstrap.php";

use HeroesofAbenez\Orm\Npc;
use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class ElitistNpcTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;

  private ElitistNpc $personality;
  private \Nette\Security\User $user;
  
  protected function setUp(): void {
    $this->personality = $this->getService(ElitistNpc::class); // @phpstan-ignore assign.propertyType
    $this->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testGetName(): void {
    Assert::same(Npc::PERSONALITY_ELITIST, $this->personality->getName());
  }

  public function testGetMood(): void {
    $identity = clone $this->user->identity;
    $npc = new Npc();
    $identity->level = 50;
    $npc->level = 51;
    Assert::same(Npc::PERSONALITY_HOSTILE, $this->personality->getMood($identity, $npc));
    $identity->level = 52;
    Assert::same(Npc::PERSONALITY_FRIENDLY, $this->personality->getMood($identity, $npc));
  }
}

$test = new ElitistNpcTest();
$test->run();
?>