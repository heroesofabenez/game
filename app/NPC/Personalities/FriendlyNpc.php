<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Utils\Karma;

final class FriendlyNpc implements NpcPersonality
{
    public function getName(): string
    {
        return Npc::PERSONALITY_FRIENDLY;
    }

    public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string
    {
        $userKarma = Karma::getPredominant($user->white_karma, $user->dark_karma);
        $npcKarma = $npc->karma;
        if (Karma::isOpposite($npcKarma, $userKarma)) {
            return Npc::PERSONALITY_HOSTILE;
        }
        return $this->getName();
    }
}
