<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

interface ICharactersRankingControlFactory
{
    public function create(): CharactersRankingControl;
}
