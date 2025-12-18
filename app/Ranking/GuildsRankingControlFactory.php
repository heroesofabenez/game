<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

interface GuildsRankingControlFactory
{
    public function create(): GuildsRankingControl;
}
