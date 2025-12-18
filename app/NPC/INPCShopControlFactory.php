<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface INPCShopControlFactory
{
    public function create(): NPCShopControl;
}
