<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface NPCDialogueControlFactory
{
    public function create(): NPCDialogueControl;
}
