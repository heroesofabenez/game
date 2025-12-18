<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC\Personalities;

interface INpcPersonality
{
    public function getName(): string;

    public function getMood(\Nette\Security\IIdentity $user, \HeroesofAbenez\Orm\Npc $npc): string;
}
