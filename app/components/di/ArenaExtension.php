<?php
namespace HeroesofAbenez\Arena\DI;

/**
 * Arena Extension
 *
 * @author Jakub Konečný
 */
class ArenaExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("pve"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVEControlFactory");
    $builder->addDefinition($this->prefix("pvp"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVPControlFactory");
  }
}
?>