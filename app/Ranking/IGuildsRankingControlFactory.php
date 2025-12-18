<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

interface IGuildsRankingControlFactory
{
    public function create(): GuildsRankingControl;
}
