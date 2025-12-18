<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface NPCQuestsControlFactory
{
    public function create(): NPCQuestsControl;
}
