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
    $this->addModels();
    $this->addCombat();
    $this->addArena();
    $this->addChat();
    $this->addNpc();
    $this->addPostOffice();
    $this->addRanking();
  }
  
  protected function addModels() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);
    $services = array(
      "Equipment", "Guild", "Intro", "Item", "Journal", "Location", "Map",
      "MapDrawer", "Permissions", "Pet", "Profile", "Request", "Quest"
    );
    foreach($services as $service) {
      $builder->addDefinition($this->prefix("model." . lcfirst($service)))
        ->setFactory("HeroesofAbenez\Model\\" . $service);
    }
    $builder->addDefinition($this->prefix("model.npc"))
      ->setFactory("HeroesofAbenez\Model\NPC");
    $builder->addDefinition($this->prefix("model.userManager"))
      ->setFactory("HeroesofAbenez\Model\UserManager", array($config["devServers"]));
    $builder->addDefinition("cache.cache")
      ->setFactory("Nette\Caching\Cache", array("@cache.storage", "data"));
    $builder->addDefinition($this->prefix("model.authorizator"))
      ->setFactory("HeroesofAbenez\Model\AuthorizatorFactory::create");
    $builder->removeDefinition("router");
    $builder->addDefinition("router")
      ->setFactory("HeroesofAbenez\Model\RouterFactory::create");
  }
  
  protected function addCombat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("combat.duel"))
      ->setFactory("HeroesofAbenez\Model\CombatDuel");
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setFactory("HeroesofAbenez\Model\CombatLogger");
    $builder->addDefinition($this->prefix("combat.logManager"))
      ->setFactory("HeroesofAbenez\Model\CombatLogManager");
  }
  
  protected function addArena() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("arena.pve"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVEControlFactory");
    $builder->addDefinition($this->prefix("arena.pvp"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVPControlFactory");
  }
  
  protected function addChat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("chat.global"))
      ->setImplement("HeroesofAbenez\Chat\GlobalChatControlFactory");
    $builder->addDefinition($this->prefix("chat.local"))
      ->setImplement("HeroesofAbenez\Chat\LocalChatControlFactory");
    $builder->addDefinition($this->prefix("chat.guild"))
      ->setImplement("HeroesofAbenez\Chat\GuildChatControlFactory");
  }
  
  protected function addNpc() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("npc.dialogue"))
      ->setImplement("HeroesofAbenez\NPC\NPCDialogueControlFactory");
    $builder->addDefinition($this->prefix("npc.shop"))
      ->setImplement("HeroesofAbenez\NPC\NPCShopControlFactory");
    $builder->addDefinition($this->prefix("npc.quests"))
      ->setImplement("HeroesofAbenez\NPC\NPCQuestsControlFactory");
  }
  
  protected function addPostOffice() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("postoffice.postoffice"))
      ->setImplement("HeroesofAbenez\Postoffice\PostofficeControlFactory");
  }
  
  protected function addRanking() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("ranking.characters"))
      ->setImplement("HeroesofAbenez\Ranking\CharactersRankingControlFactory");
    $builder->addDefinition($this->prefix("ranking.guilds"))
      ->setImplement("HeroesofAbenez\Ranking\GuildsRankingControlFactory");
  }
  
  function afterCompile(\Nette\PhpGenerator\ClassType $class) {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$this->getByType("Nette\Security\User")->authenticatedRole = "player";');
  }
}
?>