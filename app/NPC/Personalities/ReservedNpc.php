<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class ReservedNpc implements NpcPersonality
{
    public function getName(): string
    {
        return Npc::PERSONALITY_RESERVED;
    }

    public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string
    {
        return $this->getName();
    }
}
