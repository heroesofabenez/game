<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class HostileNpc implements INpcPersonality
{
    public function getName(): string
    {
        return Npc::PERSONALITY_HOSTILE;
    }

    public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string
    {
        return $this->getName();
    }
}
