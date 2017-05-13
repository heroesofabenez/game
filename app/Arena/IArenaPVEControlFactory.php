<?php
namespace HeroesofAbenez\Arena;

interface IArenaPVEControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVEControl */
  function create();
}
?>