<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface NPCShopControlFactory
{
    public function create(): NPCShopControl;
}
