<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model\DI;

use HeroesofAbenez;
use Nette\Utils\Validators;
use HeroesofAbenez\Model\IUserToCharacterMapper;
use HeroesofAbenez\Model\DevelopmentUserToCharacterMapper;

/**
 * HOA Extension
 *
 * @author Jakub Konečný
 */
final class HOAExtension extends \Nette\DI\CompilerExtension {
  protected $defaults = [
    "application" => [
      "server" => "",
    ],
    "userToCharacterMapper" => DevelopmentUserToCharacterMapper::class,
  ];
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws \RuntimeException
   */
  public function loadConfiguration(): void {
    $this->addModels();
    $this->addCombat();
    $this->addArena();
    $this->addChatCommands();
    $this->addNpc();
    $this->addPostOffice();
    $this->addRanking();
    $this->addForms();
  }
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws \RuntimeException
   */
  protected function getUserToCharacterMapper(): string {
    $config = $this->getConfig($this->defaults);
    Validators::assertField($config, "userToCharacterMapper", "string");
    $mapper = $config["userToCharacterMapper"];
    if(!class_exists($mapper) OR !is_subclass_of($mapper, IUserToCharacterMapper::class)) {
      throw new \RuntimeException("Invalid user to character mapper $mapper.");
    }
    return $mapper;
  }
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws \RuntimeException
   */
  protected function addModels(): void {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig($this->defaults);
    $builder->addDefinition($this->prefix("model.userToCharacterMapper"))
      ->setType($this->getUserToCharacterMapper());
    $builder->addDefinition($this->prefix("model.settingsRepository"))
      ->setFactory(HeroesofAbenez\Model\SettingsRepository::class, [$config]);
    $builder->addDefinition($this->prefix("model.equipment"))
      ->setType(HeroesofAbenez\Model\Equipment::class);
    $builder->addDefinition($this->prefix("model.guild"))
      ->setType(HeroesofAbenez\Model\Guild::class);
    $builder->addDefinition($this->prefix("model.intro"))
      ->setType(HeroesofAbenez\Model\Intro::class);
    $builder->addDefinition($this->prefix("model.item"))
      ->setType(HeroesofAbenez\Model\Item::class);
    $builder->addDefinition($this->prefix("model.journal"))
      ->setType(HeroesofAbenez\Model\Journal::class);
    $builder->addDefinition($this->prefix("model.location"))
      ->setType(HeroesofAbenez\Model\Location::class);
    $builder->addDefinition($this->prefix("model.map"))
      ->setType(HeroesofAbenez\Model\Map::class);
    $builder->addDefinition($this->prefix("model.mapDrawer"))
      ->setType(HeroesofAbenez\Model\MapDrawer::class);
    $builder->addDefinition($this->prefix("model.permissions"))
      ->setType(HeroesofAbenez\Model\Permissions::class);
    $builder->addDefinition($this->prefix("model.pet"))
      ->setType(HeroesofAbenez\Model\Pet::class);
    $builder->addDefinition($this->prefix("model.profile"))
      ->setType(HeroesofAbenez\Model\Profile::class);
    $builder->addDefinition($this->prefix("model.request"))
      ->setType(HeroesofAbenez\Model\Request::class);
    $builder->addDefinition($this->prefix("model.quest"))
      ->setType(HeroesofAbenez\Model\Quest::class);
    $builder->addDefinition($this->prefix("model.npc"))
      ->setType(HeroesofAbenez\Model\NPC::class);
    $builder->addDefinition($this->prefix("model.skills"))
      ->setType(HeroesofAbenez\Model\Skills::class);
    $builder->addDefinition($this->prefix("model.userManager"))
      ->setType(HeroesofAbenez\Model\UserManager::class);
    $builder->addDefinition("cache.cache")
      ->setFactory(\Nette\Caching\Cache::class, ["@cache.storage", "data"]);
    $builder->addDefinition($this->prefix("model.authorizatorFactory"))
      ->setType(HeroesofAbenez\Model\AuthorizatorFactory::class);
    $builder->addDefinition($this->prefix("model.authorizator"))
      ->setFactory("@" . HeroesofAbenez\Model\AuthorizatorFactory::class . "::create");
    $builder->removeDefinition("router");
    $builder->addDefinition($this->prefix("model.routerFactory"))
      ->setType(HeroesofAbenez\Model\RouterFactory::class);
    $builder->addDefinition("router")
      ->setFactory("@" . HeroesofAbenez\Model\RouterFactory::class . "::create");
  }
  
  protected function addCombat(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("combat.combat"))
      ->setType(HeroesofAbenez\Combat\CombatBase::class);
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setType(HeroesofAbenez\Combat\CombatLogger::class)
      ->addSetup("setTitle", ["Heroes of Abenez -"]);
    $builder->addDefinition($this->prefix("combat.logManager"))
      ->setType(HeroesofAbenez\Model\CombatLogManager::class);
    $builder->addDefinition($this->prefix("combat.helper"))
      ->setType(HeroesofAbenez\Model\CombatHelper::class);
    $builder->addDefinition($this->prefix("combat.successCalculator"))
      ->setType(HeroesofAbenez\Combat\RandomSuccessCalculator::class);
    $builder->addDefinition($this->prefix("combat.actionSelector"))
      ->setType(HeroesofAbenez\Combat\CombatActionSelector::class);
  }
  
  protected function addArena(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("arena.pve"))
      ->setImplement(HeroesofAbenez\Arena\IArenaPVEControlFactory::class);
    $builder->addDefinition($this->prefix("arena.pvp"))
      ->setImplement(HeroesofAbenez\Arena\IArenaPVPControlFactory::class);
  }
  
  protected function addChatCommands(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("chat.command.time"))
      ->setType(HeroesofAbenez\Chat\Commands\TimeCommand::class);
    $builder->addDefinition($this->prefix("chat.command.location"))
      ->setType(HeroesofAbenez\Chat\Commands\LocationCommand::class);
    $builder->addDefinition($this->prefix("chat.command.promote"))
      ->setType(HeroesofAbenez\Chat\Commands\PromoteCommand::class);
    $builder->addDefinition($this->prefix("chat.command.demote"))
      ->setType(HeroesofAbenez\Chat\Commands\DemoteCommand::class);
    $builder->addDefinition($this->prefix("chat.command.kick"))
      ->setType(HeroesofAbenez\Chat\Commands\KickCommand::class);
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
      ->setType(HeroesofAbenez\Forms\CreateCharacterFormFactory::class);
    $builder->addDefinition($this->prefix("form.createGuild"))
      ->setType(HeroesofAbenez\Forms\CreateGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.renameGuild"))
      ->setType(HeroesofAbenez\Forms\RenameGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.guildDescription"))
      ->setType(HeroesofAbenez\Forms\GuildDescriptionFormFactory::class);
    $builder->addDefinition($this->prefix("form.dissolveGuild"))
      ->setType(HeroesofAbenez\Forms\DissolveGuildFormFactory::class);
    $builder->addDefinition($this->prefix("form.customGuildRankNames"))
      ->setType(HeroesofAbenez\Forms\CustomGuildRankNamesFormFactory::class);
  }
  
  public function beforeCompile() {
    $builder = $this->getContainerBuilder();
    $chats = $builder->findByTag(HeroesofAbenez\Chat\DI\ChatExtension::TAG_CHAT);
    foreach($chats as $chat => $tags) {
      $service = $builder->getDefinition($chat);
      $service->addSetup("setTranslator");
    }
  }
  
  public function afterCompile(\Nette\PhpGenerator\ClassType $class): void {
    $initialize = $class->methods["initialize"];
    $initialize->addBody('$user = $this->getByType(?);
$user->authenticatedRole = "player";
if(!$user->isLoggedIn()) $user->login("");', [\Nette\Security\User::class]);
  }
}
?>