<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

interface ArenaPVPControlFactory
{
    public function create(): ArenaPVPControl;
}
