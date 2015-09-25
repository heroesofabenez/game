<?php
namespace HeroesofAbenez\Model\DI;

/**
 * HOA Extension
 *
 * @author Jakub Konečný
 */
class HOAExtension extends \Nette\DI\CompilerExtension {
  protected $defaults = array(
    "devServers" => array(
      "localhost", "hoa.local"
    )
  );
  
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);
    $services = array(
      "Equipment", "Guild", "Intro", "Item", "Journal", "Location", "Map",
      "MapDrawer", "Permissions", "Pet", "Profile", "Request", "Quest"
    );
    foreach($services as $service) {
      $builder->addDefinition($this->prefix(lcfirst($service)))
        ->setFactory("HeroesofAbenez\Model\\" . $service);
    }
    $builder->addDefinition($this->prefix("npc"))
      ->setFactory("HeroesofAbenez\Model\NPC");
    $builder->addDefinition($this->prefix("userManager"))
      ->setFactory("HeroesofAbenez\Model\UserManager", array($config["devServers"]));
    $builder->addDefinition("cache.cache")
      ->setFactory("Nette\Caching\Cache", array("@cache.storage", "data"));
    $builder->addDefinition($this->prefix("authorizator"))
      ->setFactory("HeroesofAbenez\Model\AuthorizatorFactory::create");
    $builder->removeDefinition("router");
    $builder->addDefinition("router")
      ->setFactory("HeroesofAbenez\Model\RouterFactory::create");
  }
  
  function afterCompile(\Nette\PhpGenerator\ClassType $class) {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$this->getByType("Nette\Security\User")->authenticatedRole = "player";');
  }
}
?>