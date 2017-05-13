<?php
namespace HeroesofAbenez\Arena;

interface IArenaPVPControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVPControl */
  function create();
}
?>