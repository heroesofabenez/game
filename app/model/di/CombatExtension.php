<?php
namespace HeroesofAbenez\Combat\DI;

/**
 * Combat Extension
 *
 * @author Jakub Konečný
 */
class CombatExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("duel"))
      ->setFactory("HeroesofAbenez\Model\CombatDuel");
    $builder->addDefinition($this->prefix("logger"))
      ->setFactory("HeroesofAbenez\Model\CombatLogger");
    $builder->addDefinition($this->prefix("logManager"))
      ->setFactory("HeroesofAbenez\Model\CombatLogManager");
  }
}
?>