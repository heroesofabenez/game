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
      ->setFactory("HeroesofAbenez\Model\\Equipment");
    $builder->addDefinition($this->prefix("model.guild"))
      ->setFactory("HeroesofAbenez\Model\\Guild");
    $builder->addDefinition($this->prefix("model.intro"))
      ->setFactory("HeroesofAbenez\Model\\Intro");
    $builder->addDefinition($this->prefix("model.item"))
      ->setFactory("HeroesofAbenez\Model\\Item");
    $builder->addDefinition($this->prefix("model.journal"))
      ->setFactory("HeroesofAbenez\Model\\Journal");
    $builder->addDefinition($this->prefix("model.location"))
      ->setFactory("HeroesofAbenez\Model\\Location");
    $builder->addDefinition($this->prefix("model.map"))
      ->setFactory("HeroesofAbenez\Model\\Map");
    $builder->addDefinition($this->prefix("model.mapDrawer"))
      ->setFactory("HeroesofAbenez\Model\\MapDrawer");
    $builder->addDefinition($this->prefix("model.permissions"))
      ->setFactory("HeroesofAbenez\Model\\Permissions");
    $builder->addDefinition($this->prefix("model.pet"))
      ->setFactory("HeroesofAbenez\Model\\Pet");
    $builder->addDefinition($this->prefix("model.profile"))
      ->setFactory("HeroesofAbenez\Model\\Profile");
    $builder->addDefinition($this->prefix("model.request"))
      ->setFactory("HeroesofAbenez\Model\\Request");
    $builder->addDefinition($this->prefix("model.quest"))
      ->setFactory("HeroesofAbenez\Model\\Quest");
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
  
  /**
   * @return void
   */
  protected function addCombat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("combat.duel"))
      ->setFactory("HeroesofAbenez\Model\CombatDuel");
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setFactory("HeroesofAbenez\Model\CombatLogger");
    $builder->addDefinition($this->prefix("combat.logManager"))
      ->setFactory("HeroesofAbenez\Model\CombatLogManager");
  }
  
  /**
   * @return void
   */
  protected function addArena() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("arena.pve"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVEControlFactory");
    $builder->addDefinition($this->prefix("arena.pvp"))
      ->setImplement("HeroesofAbenez\Arena\ArenaPVPControlFactory");
  }
  
  /**
   * @return void
   */
  protected function addChat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("chat.global"))
      ->setImplement("HeroesofAbenez\Chat\\GlobalChatControlFactory");
    $builder->addDefinition($this->prefix("chat.local"))
      ->setImplement("HeroesofAbenez\Chat\\LocalChatControlFactory");
    $builder->addDefinition($this->prefix("chat.guild"))
      ->setImplement("HeroesofAbenez\Chat\\GuildChatControlFactory");
  }
  
  /**
   * @return void
   */
  protected function addNpc() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("npc.dialogue"))
      ->setImplement("HeroesofAbenez\NPC\NPCDialogueControlFactory");
    $builder->addDefinition($this->prefix("npc.shop"))
      ->setImplement("HeroesofAbenez\NPC\NPCShopControlFactory");
    $builder->addDefinition($this->prefix("npc.quests"))
      ->setImplement("HeroesofAbenez\NPC\NPCQuestsControlFactory");
  }
  
  /**
   * @return void
   */
  protected function addPostOffice() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("postoffice.postoffice"))
      ->setImplement("HeroesofAbenez\Postoffice\PostofficeControlFactory");
  }
  
  /**
   * @return void
   */
  protected function addRanking() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("ranking.characters"))
      ->setImplement("HeroesofAbenez\Ranking\CharactersRankingControlFactory");
    $builder->addDefinition($this->prefix("ranking.guilds"))
      ->setImplement("HeroesofAbenez\Ranking\GuildsRankingControlFactory");
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
