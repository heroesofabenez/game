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
    $builder->addDefinition($this->prefix("equipment"))
      ->setFactory("HeroesofAbenez\Model\Equipment");
    $builder->addDefinition($this->prefix("guild"))
      ->setFactory("HeroesofAbenez\Model\Guild");
    $builder->addDefinition($this->prefix("intro"))
      ->setFactory("HeroesofAbenez\Model\Intro");
    $builder->addDefinition($this->prefix("item"))
      ->setFactory("HeroesofAbenez\Model\Item");
    $builder->addDefinition($this->prefix("journal"))
      ->setFactory("HeroesofAbenez\Model\Journal");
    $builder->addDefinition($this->prefix("location"))
      ->setFactory("HeroesofAbenez\Model\Location");
    $builder->addDefinition($this->prefix("map"))
      ->setFactory("HeroesofAbenez\Model\Map");
    $builder->addDefinition($this->prefix("mapDrawer"))
      ->setFactory("HeroesofAbenez\Model\MapDrawer");
    $builder->addDefinition($this->prefix("npc"))
      ->setFactory("HeroesofAbenez\Model\NPC");
    $builder->addDefinition($this->prefix("permissions"))
      ->setFactory("HeroesofAbenez\Model\Permissions");
    $builder->addDefinition($this->prefix("profile"))
      ->setFactory("HeroesofAbenez\Model\Profile");
    $builder->addDefinition($this->prefix("request"))
      ->setFactory("HeroesofAbenez\Model\Request");
    $builder->addDefinition($this->prefix("quest"))
      ->setFactory("HeroesofAbenez\Model\Quest");
    $builder->addDefinition($this->prefix("userManager"))
      ->setFactory("HeroesofAbenez\Model\UserManager");
    $builder->addDefinition($this->prefix("authorizator"))
      ->setFactory("HeroesofAbenez\Auth\Authorizator::create");
  }
}
?>