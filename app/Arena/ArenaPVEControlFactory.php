<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

interface ArenaPVEControlFactory
{
    public function create(): ArenaPVEControl;
}
