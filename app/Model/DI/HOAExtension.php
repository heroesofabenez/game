<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model\DI;

use HeroesofAbenez;

/**
 * HOA Extension
 *
 * @author Jakub Konečný
 */
class HOAExtension extends \Nette\DI\CompilerExtension {
  protected $defaults = [
    "devServers" => [
      "localhost", "hoa.local",
    ],
    "application" => [
      "server" => "",
    ]
  ];
  
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
    $this->addForms();
  }
  
  /**
   * @return void
   */
  protected function addModels() {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);
    $builder->addDefinition($this->prefix("model.settingsRepository"))
      ->setFactory(HeroesofAbenez\Model\SettingsRepository::class, [$config]);
    $builder->addDefinition($this->prefix("model.equipment"))
      ->setClass(HeroesofAbenez\Model\Equipment::class);
    $builder->addDefinition($this->prefix("model.guild"))
      ->setClass(HeroesofAbenez\Model\Guild::class);
    $builder->addDefinition($this->prefix("model.intro"))
      ->setClass(HeroesofAbenez\Model\Intro::class);
    $builder->addDefinition($this->prefix("model.item"))
      ->setClass(HeroesofAbenez\Model\Item::class);
    $builder->addDefinition($this->prefix("model.journal"))
      ->setClass(HeroesofAbenez\Model\Journal::class);
    $builder->addDefinition($this->prefix("model.location"))
      ->setClass(HeroesofAbenez\Model\Location::class);
    $builder->addDefinition($this->prefix("model.map"))
      ->setClass(HeroesofAbenez\Model\Map::class);
    $builder->addDefinition($this->prefix("model.mapDrawer"))
      ->setClass(HeroesofAbenez\Model\MapDrawer::class);
    $builder->addDefinition($this->prefix("model.permissions"))
      ->setClass(HeroesofAbenez\Model\Permissions::class);
    $builder->addDefinition($this->prefix("model.pet"))
      ->setClass(HeroesofAbenez\Model\Pet::class);
    $builder->addDefinition($this->prefix("model.profile"))
      ->setClass(HeroesofAbenez\Model\Profile::class);
    $builder->addDefinition($this->prefix("model.request"))
      ->setClass(HeroesofAbenez\Model\Request::class);
    $builder->addDefinition($this->prefix("model.quest"))
      ->setClass(HeroesofAbenez\Model\Quest::class);
    $builder->addDefinition($this->prefix("model.npc"))
      ->setClass(HeroesofAbenez\Model\NPC::class);
    $builder->addDefinition($this->prefix("model.skills"))
      ->setClass(HeroesofAbenez\Model\Skills::class);
    $builder->addDefinition($this->prefix("model.userManager"))
      ->setClass(HeroesofAbenez\Model\UserManager::class);
    $builder->addDefinition("cache.cache")
      ->setFactory(\Nette\Caching\Cache::class, ["@cache.storage", "data"]);
    $builder->addDefinition($this->prefix("model.authorizator"))
      ->setFactory(HeroesofAbenez\Model\AuthorizatorFactory::class . "::create");
    $builder->removeDefinition("router");
    $builder->addDefinition("router")
      ->setFactory(HeroesofAbenez\Model\RouterFactory::class . "::create");
  }
  
  /**
   * @return void
   */
  protected function addCombat() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("combat.duel"))
      ->setClass(HeroesofAbenez\Model\CombatDuel::class);
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setClass(HeroesofAbenez\Model\CombatLogger::class);
    $builder->addDefinition($this->prefix("combat.logManager"))
      ->setClass(HeroesofAbenez\Model\CombatLogManager::class);
    $builder->addDefinition($this->prefix("combat.helper"))
      ->setClass(HeroesofAbenez\Model\CombatHelper::class);
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
    $builder->addDefinition($this->prefix("chat.commandsProcessor"))
      ->setClass(HeroesofAbenez\Chat\ChatCommandsProcessor::class);
    $builder->addDefinition($this->prefix("chat.command.time"))
      ->setClass(HeroesofAbenez\Chat\Commands\TimeCommand::class);
    $builder->addDefinition($this->prefix("chat.command.location"))
      ->setClass(HeroesofAbenez\Chat\Commands\LocationCommand::class);
    $builder->addDefinition($this->prefix("chat.command.promote"))
      ->setClass(HeroesofAbenez\Chat\Commands\PromoteCommand::class);
    $builder->addDefinition($this->prefix("chat.command.demote"))
      ->setClass(HeroesofAbenez\Chat\Commands\DemoteCommand::class);
    $builder->addDefinition($this->prefix("chat.command.kick"))
      ->setClass(HeroesofAbenez\Chat\Commands\KickCommand::class);
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
   * @return void
   */
  protected function addForms() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("form.createCharacter"))
      ->setClass(HeroesofAbenez\Forms\CreateCharacterFormFactory::class);
    $builder->addDefinition($this->prefix("form.createGuild"))
      ->setClass(HeroesofAbenez\Forms\CreateGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.renameGuild"))
      ->setClass(HeroesofAbenez\Forms\RenameGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.guildDescription"))
      ->setClass(HeroesofAbenez\Forms\GuildDescriptionFormFactory::class);
    $builder->addDefinition($this->prefix("form.dissolveGuild"))
      ->setClass(HeroesofAbenez\Forms\DissolveGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.customGuildRankNames"))
      ->setClass(HeroesofAbenez\Forms\CustomGuildRankNamesFormFactory::class);
  }
  
  function beforeCompile() {
    $builder = $this->getContainerBuilder();
    $processor = $builder->getDefinition($this->prefix("chat.commandsProcessor"));
    $chatCommands = $builder->findByType(\HeroesofAbenez\Entities\ChatCommand::class);
    foreach($chatCommands as $command) {
      $processor->addSetup("addCommand", [$command]);
    }
  }
  
  /**
   * @param \Nette\PhpGenerator\ClassType $class
   * @return void
   */
  function afterCompile(\Nette\PhpGenerator\ClassType $class) {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$this->getByType(?)->authenticatedRole = "player";', [\Nette\Security\User::class]);
  }
}
?>