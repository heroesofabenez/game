<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Forms\CustomGuildRankNamesFormFactory;
use Nette\Application\UI\Form,
    HeroesofAbenez\Model\GuildNotFoundException,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\GrandmasterCannotLeaveGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuildException,
    HeroesofAbenez\Model\CannotPromoteHigherRanksException,
    HeroesofAbenez\Model\CannotPromoteToGrandmasterException,
    HeroesofAbenez\Model\CannotHaveMoreDeputiesException,
    HeroesofAbenez\Model\CannotDemoteHigherRanksException,
    HeroesofAbenez\Model\CannotDemoteLowestRankException,
    HeroesofAbenez\Model\CannotKickHigherRanksException,
    HeroesofAbenez\Forms\CreateGuildFormFactory,
    HeroesofAbenez\Forms\RenameGuildFormFactory,
    HeroesofAbenez\Forms\GuildDescriptionFormFactory,
    HeroesofAbenez\Forms\DissolveGuildFormFactory;

  /**
   * Presenter Guild
   * 
   * @author Jakub Konečný
   */
final class GuildPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  
  /**
   */
  public function __construct(\HeroesofAbenez\Model\Guild $model, \HeroesofAbenez\Model\Permissions $permissionsModel) {
    parent::__construct();
    $this->model = $model;
    $this->permissionsModel = $permissionsModel;
  }
  
  /**
   * Redirect player to guild page if he is already in guild
   */
  protected function inGuild(): void {
    $guild = $this->user->identity->guild;
    if($guild > 0) {
      $this->flashMessage($this->translator->translate("errors.guild.inGuild"));
      $this->forward("default");
    }
  }
  
  /**
   * Redirect player to noguild if he is not in guild
   * 
   * @param bool $warning Whatever to print a warning (via flash message)
  */
  protected function notInGuild(bool $warning = true): void {
    $guild = $this->user->identity->guild;
    if($guild == 0) {
      if($warning) {
        $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
      }
      $this->forward("noguild");
    }
  }
  
  public function actionDefault(): void {
    $this->notInGuild(false);
  }
  
  public function renderDefault(): void {
    $this->template->guild = $this->model->view($this->user->identity->guild);
    $this->template->canManage = $this->user->isAllowed("guild", "manage");
    $this->template->canInvite = $this->user->isAllowed("guild", "invite");
  }
  
  public function renderView(int $id): void {
    if($id == 0) {
      $this->forward("notfound");
    }
    $data = $this->model->view($id);
    if(is_null($data)) {
      $this->forward("notfound");
    }
    $this->template->guild = $data;
  }
  
  public function actionMembers(): void {
    $this->notInGuild();
  }
  
  public function renderMembers(): void {
    $this->template->members = $this->model->guildMembers($this->user->identity->guild, [], true);
    $this->template->canPromote = $this->user->isAllowed("guild", "promote");
    $this->template->canKick = $this->user->isAllowed("guild", "kick");
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $this->user->roles[0]) {
        $rankId = $role["id"];
        break;
      }
    }
    $this->template->rankId = $rankId;
  }
  
  /**
   * Creates form for creating guild
   */
  protected function createComponentCreateGuildForm(CreateGuildFormFactory $factory): Form {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->user->logout();
      $this->flashMessage($this->translator->translate("messages.guild.created"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  public function actionCreate(): void {
    $this->inGuild();
    $this->template->haveForm = true;
  }
  
  public function actionJoin(int $id = null): void {
    $this->inGuild();
    if(is_null($id)) {
      return;
    }
    try {
      $this->model->sendApplication($id);
      $this->flashMessage($this->translator->translate("messages.guild.applicationSent"));
      $this->redirect("Guild:");
    } catch(GuildNotFoundException $e) {
      $this->forward("notfound");
    }
  }
  
  public function renderJoin(int $id = null): void {
    $guilds = $this->model->listOfGuilds();
    $this->template->guilds = $guilds;
    $apps = $this->model->haveUnresolvedApplication();
    if($apps) {
      $this->flashMessage($this->translator->translate("messages.guild.unresolvedApplication"));
    }
  }
  
  public function actionLeave(): void {
    $this->notInGuild();
    try {
      $this->model->leave();
      $this->flashMessage($this->translator->translate("messages.guild.left"));
      $this->user->logout();
      $this->forward("default");
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
      $this->redirect("Guild:");
    } catch(GrandmasterCannotLeaveGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.grandmasterCannotLeave"));
      $this->redirect("Guild:");
    }
  }
  
  public function actionManage(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotManage"));
      $this->redirect("Guild:");
    }
  }
  
  public function renderManage(): void {
    $this->template->canRename = $this->user->isAllowed("guild", "rename");
    $this->template->canDissolve = $this->user->isAllowed("guild", "dissolve");
    $this->template->canChangeRankNames = $this->user->isAllowed("guild", "changeRankNames");
  }
  
  public function actionRename(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "rename")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotRename"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  public function actionDissolve(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "dissolve")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDissolve"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  /**
   * Creates form for dissolving guild
   */
  protected function createComponentDissolveGuildForm(DissolveGuildFormFactory $factory): Form {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.dissolved"));
      $this->user->logout();
      $this->redirect("Guild:noguild");
    };
    return $form;
  }
  
  /**
   * Creates form for renaming guild
   */
  protected function createComponentRenameGuildForm(RenameGuildFormFactory $factory): Form {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.renamed"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  public function actionPromote(int $id): void {
    try{
      $this->model->promote($id);
      $this->flashMessage($this->translator->translate("messages.guild.promoted"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotPromoteHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotPromoteHigherRanks"));
    } catch(CannotPromoteToGrandmasterException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotPromoteToGrandmaster"));
    } catch(CannotHaveMoreDeputiesException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotHaveMoreDeputies"));
    }
    $this->redirect("Guild:");
  }
  
  public function actionDemote(int $id): void {
    try{
      $this->model->demote($id);
      $this->flashMessage($this->translator->translate("messages.guild.demoted"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotDemoteHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDemoteHigherRanks"));
    } catch(CannotDemoteLowestRankException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDemoteLowestRank"));
    }
    $this->redirect("Guild:");
  }
  
  public function actionKick(int $id): void {
    try {
      $this->model->kick($id);
      $this->flashMessage($this->translator->translate("messages.guild.kicked"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotKickHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotKickHigherRanks"));
    }
    $this->redirect("Guild:");
  }
  
  public function actionDescription(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotChangeDescription"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  /**
   * Creates form for changing guild's description
  */
  protected function createComponentGuildDescriptionForm(GuildDescriptionFormFactory $factory): Form {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.descriptionChanged"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  public function actionApplications(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "invite")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotManageApps"));
      $this->redirect("Guild:");
    }
  }
  
  public function renderApplications(): void {
    $this->template->apps = $this->model->showApplications($this->user->identity->guild);
  }
  
  public function actionRankNames(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "changeRankNames")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotChangeRankNames"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  protected function createComponentCustomGuildRankNamesForm(CustomGuildRankNamesFormFactory $factory): Form {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.customRankNamesSet"));
      $this->redirect("Guild:");
    };
    return $form;
  }
}
?>