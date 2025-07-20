<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

require __DIR__ . "/../../../bootstrap.php";

use HeroesofAbenez\Orm\Character;
use HeroesofAbenez\Orm\Npc;
use Tester\Assert;

final class MisogynistNpcTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;

  private MisogynistNpc $personality;
  private \Nette\Security\User $user;
  
  protected function setUp(): void {
    $this->personality = $this->getService(MisogynistNpc::class); // @phpstan-ignore assign.propertyType
    $this->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testGetName(): void {
    Assert::same(Npc::PERSONALITY_MISOGYNIST, $this->personality->getName());
  }

  public function testGetMood(): void {
    $identity = clone $this->user->identity;
    $npc = new Npc();
    $identity->gender = Character::GENDER_MALE;
    Assert::same(Npc::PERSONALITY_CRAZY, $this->personality->getMood($identity, $npc));
    $identity->gender = Character::GENDER_FEMALE;
    Assert::same(Npc::PERSONALITY_HOSTILE, $this->personality->getMood($identity, $npc));
  }
}

$test = new MisogynistNpcTest();
$test->run();
?>