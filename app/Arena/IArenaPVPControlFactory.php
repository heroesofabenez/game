<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

interface IArenaPVPControlFactory {
  public function create(): ArenaPVPControl;
}
?>