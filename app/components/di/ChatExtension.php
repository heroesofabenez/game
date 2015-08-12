<?php
namespace HeroesofAbenez\Chat\DI;

/**
 * Chat Extension
 *
 * @author Jakub Konečný
 */
class ChatExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("global"))
      ->setImplement("HeroesofAbenez\Chat\GlobalChatControlFactory");
    $builder->addDefinition($this->prefix("local"))
      ->setImplement("HeroesofAbenez\Chat\LocalChatControlFactory");
    $builder->addDefinition($this->prefix("guild"))
      ->setImplement("HeroesofAbenez\Chat\GuildChatControlFactory");
  }
}
?>