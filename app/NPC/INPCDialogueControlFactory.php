<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface INPCDialogueControlFactory
{
    public function create(): NPCDialogueControl;
}
