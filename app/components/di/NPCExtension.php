<?php
namespace HeroesofAbenez\NPC\DI;

/**
 * NPC Extension
 *
 * @author Jakub Konečný
 */
class NPCExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("dialogue"))
      ->setImplement("HeroesofAbenez\NPC\NPCDialogueControlFactory");
    $builder->addDefinition($this->prefix("shop"))
      ->setImplement("HeroesofAbenez\NPC\NPCShopControlFactory");
    $builder->addDefinition($this->prefix("quests"))
      ->setImplement("HeroesofAbenez\NPC\NPCQuestsControlFactory");
  }
}
?>