<?php
namespace HeroesofAbenez\Ranking;

interface IGuildsRankingControlFactory {
  /** @return \HeroesofAbenez\Ranking\GuildsRankingControl */
  function create();
}
?>