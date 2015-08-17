<?php
namespace HeroesofAbenez\Model\DI;

/**
 * Combat Extension
 *
 * @author Jakub Konečný
 */
class HOAExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $services = array(
      "Equipment", "Guild", "Intro", "Item", "Journal", "Location", "Map",
      "MapDrawer", "Permissions", "Pet", "Profile", "Request", "Quest", "UserManager"
    );
    foreach($services as $service) {
      $builder->addDefinition($this->prefix(lcfirst($service)))
        ->setFactory("HeroesofAbenez\Model\\" . $service);
    }
    $builder->addDefinition($this->prefix("npc"))
      ->setFactory("HeroesofAbenez\Model\NPC");
    $builder->addDefinition($this->prefix("authorizator"))
      ->setFactory("HeroesofAbenez\Model\Authorizator::create");
    $builder->removeDefinition("router");
    $builder->addDefinition("router")
      ->setFactory("HeroesofAbenez\Model\RouterFactory::create");
  }
}
?>