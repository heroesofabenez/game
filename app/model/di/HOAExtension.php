<?php
namespace HeroesofAbenez\Model\DI;

use HeroesofAbenez;

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
  
  /**
   * @return void
   */
  function loadConfiguration() {
    $this->addModels();
    $this->addCombat();
    $this->addArena();
    $this->addChat();
    $this->addNpc();
    $this->addPostOffice();
    $this->addRanking();
  }
  
  /**
   * @return void
   */
  protected function addModels() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);
    $builder->addDefinition($this->prefix("model.equipment"))
      ->setFactory(HeroesofAbenez\Model\Equipment::class);
    $builder->addDefinition($this->prefix("model.guild"))
      ->setFactory(HeroesofAbenez\Model\Guild::class);
    $builder->addDefinition($this->prefix("model.intro"))
      ->setFactory(HeroesofAbenez\Model\Intro::class);
    $builder->addDefinition($this->prefix("model.item"))
      ->setFactory(HeroesofAbenez\Model\Item::class);
    $builder->addDefinition($this->prefix("model.journal"))
      ->setFactory(HeroesofAbenez\Model\Journal::class);
    $builder->addDefinition($this->prefix("model.location"))
      ->setFactory(HeroesofAbenez\Model\Location::class);
    $builder->addDefinition($this->prefix("model.map"))
      ->setFactory(HeroesofAbenez\Model\Map::class);
    $builder->addDefinition($this->prefix("model.mapDrawer"))
      ->setFactory(HeroesofAbenez\Model\MapDrawer::class);
    $builder->addDefinition($this->prefix("model.permissions"))
      ->setFactory(HeroesofAbenez\Model\Permissions::class);
    $builder->addDefinition($this->prefix("model.pet"))
      ->setFactory(HeroesofAbenez\Model\Pet::class);
    $builder->addDefinition($this->prefix("model.profile"))
      ->setFactory(HeroesofAbenez\Model\Profile::class);
    $builder->addDefinition($this->prefix("model.request"))
      ->setFactory(HeroesofAbenez\Model\Request::class);
    $builder->addDefinition($this->prefix("model.quest"))
      ->setFactory(HeroesofAbenez\Model\Quest::class);
    $builder->addDefinition($this->prefix("model.npc"))
      ->setFactory(HeroesofAbenez\Model\NPC::class);
    $builder->addDefinition($this->prefix("model.userManager"))
      ->setFactory(HeroesofAbenez\Model\UserManager::class, array($config["devServers"]));
    $builder->addDefinition("cache.cache")
      ->setFactory("Nette\Caching\Cache", array("@cache.storage", "data"));
    $builder->addDefinition($this->prefix("model.authorizator"))
      ->setFactory("HeroesofAbenez\Model\AuthorizatorFactory::create");
    $builder->removeDefinition("router");
    $builder->addDefinition("router")
      ->setFactory("HeroesofAbenez\Model\RouterFactory::create");
  }
  
  /**
   * @return void
   */
  protected function addCombat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("combat.duel"))
      ->setFactory(HeroesofAbenez\Model\CombatDuel::class);
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setFactory(HeroesofAbenez\Model\CombatLogger::class);
    $builder->addDefinition($this->prefix("combat.logManager"))
      ->setFactory(HeroesofAbenez\Model\CombatLogManager::class);
  }
  
  /**
   * @return void
   */
  protected function addArena() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("arena.pve"))
      ->setImplement(HeroesofAbenez\Arena\ArenaPVEControlFactory::class);
    $builder->addDefinition($this->prefix("arena.pvp"))
      ->setImplement(HeroesofAbenez\Arena\ArenaPVPControlFactory::class);
  }
  
  /**
   * @return void
   */
  protected function addChat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("chat.global"))
      ->setImplement(HeroesofAbenez\Chat\GlobalChatControlFactory::class);
    $builder->addDefinition($this->prefix("chat.local"))
      ->setImplement(HeroesofAbenez\Chat\LocalChatControlFactory::class);
    $builder->addDefinition($this->prefix("chat.guild"))
      ->setImplement(HeroesofAbenez\Chat\GuildChatControlFactory::class);
  }
  
  /**
   * @return void
   */
  protected function addNpc() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("npc.dialogue"))
      ->setImplement(HeroesofAbenez\NPC\NPCDialogueControlFactory::class);
    $builder->addDefinition($this->prefix("npc.shop"))
      ->setImplement(HeroesofAbenez\NPC\NPCShopControlFactory::class);
    $builder->addDefinition($this->prefix("npc.quests"))
      ->setImplement(HeroesofAbenez\NPC\NPCQuestsControlFactory::class);
  }
  
  /**
   * @return void
   */
  protected function addPostOffice() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("postoffice.postoffice"))
      ->setImplement(HeroesofAbenez\Postoffice\PostofficeControlFactory::class);
  }
  
  /**
   * @return void
   */
  protected function addRanking() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("ranking.characters"))
      ->setImplement(HeroesofAbenez\Ranking\CharactersRankingControlFactory::class);
    $builder->addDefinition($this->prefix("ranking.guilds"))
      ->setImplement(HeroesofAbenez\Ranking\GuildsRankingControlFactory::class);
  }
  
  /**
   * @param \Nette\PhpGenerator\ClassType $class
   * @return void
   */
  function afterCompile(\Nette\PhpGenerator\ClassType $class) {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$this->getByType("Nette\Security\User")->authenticatedRole = "player";');
  }
}
?>
