<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

use HeroesofAbenez\Orm\Npc;

final class MisogynistNpc implements INpcPersonality
{
    public function getName(): string
    {
        return Npc::PERSONALITY_MISOGYNIST;
    }

    public function getMood(\Nette\Security\IIdentity $user, Npc $npc): string
    {
        if ($user->gender !== \HeroesofAbenez\Orm\Character::GENDER_MALE) {
            return Npc::PERSONALITY_HOSTILE;
        }
        return Npc::PERSONALITY_CRAZY;
    }
}
