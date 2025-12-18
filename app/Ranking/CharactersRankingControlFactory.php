<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

interface CharactersRankingControlFactory
{
    public function create(): CharactersRankingControl;
}
