<?php
namespace HeroesofAbenez\Ranking;

interface ICharactersRankingControlFactory {
  /** @return \HeroesofAbenez\Ranking\CharactersRankingControl */
  function create();
}
?>