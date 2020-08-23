<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model\DI;

use HeroesofAbenez;
use Nette\Schema\Expect;
use HeroesofAbenez\Model\IUserToCharacterMapper;
use HeroesofAbenez\Model\DevelopmentUserToCharacterMapper;

/**
 * HOA Extension
 *
 * @author Jakub Konečný
 * @method array getConfig()
 */
final class HOAExtension extends \Nette\DI\CompilerExtension {
  public function getConfigSchema(): \Nette\Schema\Schema {
    return Expect::structure([
      "application" => Expect::structure([
        "server" => Expect::string(""),
      ])->castTo("array"),
      "userToCharacterMapper" => Expect::type("class")->default(DevelopmentUserToCharacterMapper::class),
    ])->castTo("array");
  }

  /**
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
    $this->addNpcPersonalities();
  }
  
  /**
   * @throws \RuntimeException
   */
  protected function getUserToCharacterMapper(): string {
    $config = $this->getConfig();
    $mapper = $config["userToCharacterMapper"];
    if(!class_exists($mapper) || !is_subclass_of($mapper, IUserToCharacterMapper::class)) {
      throw new \RuntimeException("Invalid user to character mapper $mapper.");
    }
    return $mapper;
  }
  
  /**
   * @throws \RuntimeException
   */
  protected function addModels(): void {
    $builder = $this->getContainerBuilder();
    $config = $this->getConfig();
    $builder->addDefinition($this->prefix("model.userToCharacterMapper"))
      ->setType($this->getUserToCharacterMapper());
    $builder->addDefinition($this->prefix("model.settingsRepository"))
      ->setFactory(HeroesofAbenez\Model\SettingsRepository::class, [$config]);
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
      ->setFactory(HeroesofAbenez\Model\MapDrawer::class, [$builder->parameters['wwwDir']]);
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
    $builder->addDefinition($this->prefix("model.characterBuilder"))
      ->setType(HeroesofAbenez\Model\CharacterBuilder::class);
    $builder->addDefinition($this->prefix("model.friends"))
      ->setType(HeroesofAbenez\Model\Friends::class);
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
      ->setType(HeroesofAbenez\Combat\CombatBase::class)
      ->addSetup('$service->onCombatEnd[] = ?', [["@" . $this->prefix("combat.helper"), "wearOutEquipment"]]);
    $builder->addDefinition($this->prefix("combat.logger"))
      ->setType(HeroesofAbenez\Combat\CombatLogger::class)
      ->addSetup('$service->title = ?', ["Heroes of Abenez -"]);
    $builder->addDefinition($this->prefix("combat.logRender"))
      ->setType(HeroesofAbenez\Combat\TextCombatLogRender::class);
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
    $builder->addFactoryDefinition($this->prefix("arena.pve"))
      ->setImplement(HeroesofAbenez\Arena\IArenaPVEControlFactory::class);
    $builder->addFactoryDefinition($this->prefix("arena.pvp"))
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
    $builder->addFactoryDefinition($this->prefix("npc.dialogue"))
      ->setImplement(HeroesofAbenez\NPC\INPCDialogueControlFactory::class);
    $builder->addFactoryDefinition($this->prefix("npc.shop"))
      ->setImplement(HeroesofAbenez\NPC\INPCShopControlFactory::class);
    $builder->addFactoryDefinition($this->prefix("npc.quests"))
      ->setImplement(HeroesofAbenez\NPC\INPCQuestsControlFactory::class);
  }
  
  protected function addPostOffice(): void {
    $builder = $this->getContainerBuilder();
    $builder->addFactoryDefinition($this->prefix("postoffice.postoffice"))
      ->setImplement(HeroesofAbenez\Postoffice\IPostofficeControlFactory::class);
  }
  
  protected function addRanking(): void {
    $builder = $this->getContainerBuilder();
    $builder->addFactoryDefinition($this->prefix("ranking.characters"))
      ->setImplement(HeroesofAbenez\Ranking\ICharactersRankingControlFactory::class);
    $builder->addFactoryDefinition($this->prefix("ranking.guilds"))
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
    $builder->addDefinition($this->prefix($this->prefix("form.donateToGuild")))
      ->setType(HeroesofAbenez\Forms\DonateToGuildFormFactory::class);
  }

  protected function addNpcPersonalities(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("npc.personalityChooser"))
      ->setType(HeroesofAbenez\Model\NpcPersonalityChooser::class);
    $classes = [
      HeroesofAbenez\NPC\Personalities\CrazyNpc::class, HeroesofAbenez\NPC\Personalities\ElitistNpc::class,
      HeroesofAbenez\NPC\Personalities\FriendlyNpc::class, HeroesofAbenez\NPC\Personalities\HostileNpc::class,
      HeroesofAbenez\NPC\Personalities\MisogynistNpc::class, HeroesofAbenez\NPC\Personalities\RacistNpc::class,
      HeroesofAbenez\NPC\Personalities\ReservedNpc::class, HeroesofAbenez\NPC\Personalities\ShyNpc::class,
      HeroesofAbenez\NPC\Personalities\TeachingNpc::class,
    ];
    foreach($classes as $index => $class) {
      $builder->addDefinition($this->prefix("npcPersonality." . ($index + 1)))
        ->setType($class);
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