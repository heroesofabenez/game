<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class RacistNpc implements INpcPersonality {
  public function getName(): string {
    return Npc::PERSONALITY_RACIST;
  }

  public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string {
    if($user->race !== $npc->race->id) {
      return Npc::PERSONALITY_HOSTILE;
    }
    return Npc::PERSONALITY_CRAZY;
  }
}
?>