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
  
  public function loadConfiguration(): void {
    $this->addModels();
    $this->addCombat();
    $this->addArena();
    $this->addChat();
    $this->addNpc();
    $this->addPostOffice();
    $this->addRanking();
    $this->addForms();
  }
  
  protected function addModels(): void {
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
    $builder->addDefinition($this->prefix("model.authorizatorFactory"))
      ->setClass(HeroesofAbenez\Model\AuthorizatorFactory::class);
    $builder->addDefinition($this->prefix("model.authorizator"))
      ->setFactory("@". HeroesofAbenez\Model\AuthorizatorFactory::class . "::create");
    $builder->removeDefinition("router");
    $builder->addDefinition($this->prefix("model.routerFactory"))
      ->setClass(HeroesofAbenez\Model\RouterFactory::class);
    $builder->addDefinition("router")
      ->setFactory("@" . HeroesofAbenez\Model\RouterFactory::class . "::create");
  }
  
  protected function addCombat(): void {
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
  
  protected function addArena(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("arena.pve"))
      ->setImplement(HeroesofAbenez\Arena\IArenaPVEControlFactory::class);
    $builder->addDefinition($this->prefix("arena.pvp"))
      ->setImplement(HeroesofAbenez\Arena\IArenaPVPControlFactory::class);
  }
  
  protected function addChat(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("chat.global"))
      ->setImplement(HeroesofAbenez\Chat\IGlobalChatControlFactory::class);
    $builder->addDefinition($this->prefix("chat.local"))
      ->setImplement(HeroesofAbenez\Chat\ILocalChatControlFactory::class);
    $builder->addDefinition($this->prefix("chat.guild"))
      ->setImplement(HeroesofAbenez\Chat\IGuildChatControlFactory::class);
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
  
  protected function addNpc(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("npc.dialogue"))
      ->setImplement(HeroesofAbenez\NPC\INPCDialogueControlFactory::class);
    $builder->addDefinition($this->prefix("npc.shop"))
      ->setImplement(HeroesofAbenez\NPC\INPCShopControlFactory::class);
    $builder->addDefinition($this->prefix("npc.quests"))
      ->setImplement(HeroesofAbenez\NPC\INPCQuestsControlFactory::class);
  }
  
  protected function addPostOffice(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("postoffice.postoffice"))
      ->setImplement(HeroesofAbenez\Postoffice\IPostofficeControlFactory::class);
  }
  
  protected function addRanking(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("ranking.characters"))
      ->setImplement(HeroesofAbenez\Ranking\ICharactersRankingControlFactory::class);
    $builder->addDefinition($this->prefix("ranking.guilds"))
      ->setImplement(HeroesofAbenez\Ranking\IGuildsRankingControlFactory::class);
  }
  
  protected function addForms(): void {
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
  
  public function beforeCompile(): void {
    $builder = $this->getContainerBuilder();
    $processor = $builder->getDefinition($this->prefix("chat.commandsProcessor"));
    $chatCommands = $builder->findByType(HeroesofAbenez\Chat\ChatCommand::class);
    foreach($chatCommands as $command) {
      $processor->addSetup("addCommand", [$command]);
    }
  }
  
  public function afterCompile(\Nette\PhpGenerator\ClassType $class): void {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$user = $this->getByType(?);
$user->authenticatedRole = "player";
if(!$user->isLoggedIn()) $user->login();', [\Nette\Security\User::class]);
  }
}
?>