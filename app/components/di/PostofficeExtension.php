<?php
namespace HeroesofAbenez\Postoffice\DI;

/**
 * Postoffice Extension
 *
 * @author Jakub Konečný
 */
class PostofficeExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("postoffice"))
      ->setImplement("HeroesofAbenez\Postoffice\PostofficeControlFactory");
  }
}
?>