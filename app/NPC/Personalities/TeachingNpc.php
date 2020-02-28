<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class TeachingNpc implements INpcPersonality {
  public function getName(): string {
    return Npc::PERSONALITY_TEACHING;
  }

  public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string {
    if($user->level > $npc->level) {
      return Npc::PERSONALITY_FRIENDLY;
    }
    return $this->getName();
  }
}
?>