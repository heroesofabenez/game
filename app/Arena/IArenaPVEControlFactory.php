<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

interface IArenaPVEControlFactory {
  public function create(): ArenaPVEControl;
}
?>