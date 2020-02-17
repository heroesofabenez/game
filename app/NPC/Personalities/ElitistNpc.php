<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class ElitistNpc implements INpcPersonality {
  public function getName(): string {
    return Npc::PERSONALITY_ELITIST;
  }

  public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string {
    if($user->level < $npc->level) {
      return Npc::PERSONALITY_HOSTILE;
    }
    return Npc::PERSONALITY_FRIENDLY;
  }
}
?>