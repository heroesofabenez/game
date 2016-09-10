<?php
namespace HeroesofAbenez\Presenters;

use Nette\Application\UI\Form,
    HeroesofAbenez\Model\NameInUseException,
    HeroesofAbenez\Model\GuildNotFoundException,
    HeroesofAbenez\Model\NotInGuildException,
    HeroesofAbenez\Model\GrandmasterCannotLeaveGuildException,
    HeroesofAbenez\Model\MissingPermissionsException,
    HeroesofAbenez\Model\PlayerNotFoundException,
    HeroesofAbenez\Model\PlayerNotInGuild,
    HeroesofAbenez\Model\CannotPromoteHigherRanksException,
    HeroesofAbenez\Model\CannotPromoteToGrandmaster,
    HeroesofAbenez\Model\CannotHaveMoreDeputies,
    HeroesofAbenez\Forms\CreateGuildFormFactory,
    HeroesofAbenez\Forms\RenameGuildFormFactory,
    HeroesofAbenez\Forms\GuildDescriptionFormFactory,
    HeroesofAbenez\Forms\DissolveGuildFormFactory;

  /**
   * Presenter Guild
   * 
   * @author Jakub Konečný
   */
class GuildPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Guild @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Permissions @autowire */
  protected $permissionsModel;
  
  /**
   * Redirect player to guild page if he is already in guild
   * 
   * @return void
   */
  function inGuild() {
    $guild = $this->user->identity->guild;
    if($guild > 0) {
      $this->flashMessage($this->translator->translate("errors.guild.inGuild"));
      $this->forward("default");
    }
  }
  
  /**
   * Redirect player to noguild if he is not in guild
   * 
   * @param bool $warrning Whetever to print a warrning (via flash message)
   * @return void
  */
  function notInGuild($warrning = true) {
    $guild = $this->user->identity->guild;
    if($guild == 0) {
      if($warrning) { $this->flashMessage($this->translator->translate("errors.guild.notInGuild")); }
      $this->forward("noguild");
    }
  }
  
  /**
   * @return void
   */
  function actionDefault() {
    $this->notInGuild(false);
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $guild = $this->model->guildData($this->user->identity->guild)->toArray();
    foreach($guild as $key => $value) {
      $this->template->$key = $value;
    }
    $this->template->guild = $guild;
    $this->template->canManage = $this->user->isAllowed("guild", "manage");
    $this->template->canInvite = $this->user->isAllowed("guild", "invite");
  }
  
  /**
   * @param int $id Id of guild to view
   * @return void
   */
  function renderView($id) {
    if($id == 0) $this->forward("notfound");
    $data = $this->model->view($id);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function actionMembers() {
    $this->notInGuild();
  }
  
  /**
   * @return void
   */
  function renderMembers() {
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
   * 
   * @param CreateGuildFormFactory $factory
   * @return Form
   */
  protected function createComponentCreateGuildForm(CreateGuildFormFactory $factory) {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->user->logout();
      $this->flashMessage($this->translator->translate("messages.guild.created"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  /**
   * @return void
   */
  function actionCreate() {
    $this->inGuild();
    $this->template->haveForm = true;
  }
  
  /**
   * @param int $id Guild to join   
   * @return void
   */
  function actionJoin($id) {
    $this->inGuild();
    try {
      $this->model->sendApplication($id);
      $this->flashMessage($this->translator->translate("messages.guild.applicationSent"));
      $this->redirect("Guild:");
    } catch(GuildNotFoundException $e) {
      $this->forward("notfound");
    }
  }
  
  /**
   * @return void
   */
  function renderJoin() {
    $guilds = $this->model->listOfGuilds();
    $this->template->guilds = $guilds;
    $apps = $this->model->haveUnresolvedApplication();
    if($apps) $this->flashMessage($this->translator->translate("messages.guild.unresolvedApplication"));
  }
  
  /**
   * @return void
  */
  function actionLeave() {
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
  
  /**
   * @return void
  */     
  function actionManage() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotManage"));
      $this->redirect("Guild:");
    }
  }
  
  /**
   * @return void
  */ 
  function renderManage() {
    $this->template->canRename = $this->user->isAllowed("guild", "rename");
    $this->template->canDissolve = $this->user->isAllowed("guild", "dissolve");
  }
  
  /**
   * @return void
   */
  function actionRename() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "rename")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotRename"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  /**
   * @return void
   */
  function actionDissolve() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "dissolve")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDissolve"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  /**
   * Creates form for dissolving guild
   *
   * @param DissolveGuildFormFactory $factory
   * @return Form
   */
  protected function createComponentDissolveGuildForm(DissolveGuildFormFactory $factory) {
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
   *
   * @param RenameGuildFormFactory $factory
   * @return Form
   */
  protected function createComponentRenameGuildForm(RenameGuildFormFactory $factory) {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.renamed"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  /**
   * @return void
   */
  function actionPromote($id) {
    try{
      $this->model->promote($id);
      $this->flashMessage($this->translator->translate("messages.guild.promoted"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuild $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotPromoteHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotPromoteHigherRanks"));
    } catch(CannotPromoteToGrandmaster $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotPromoteToGranmaster"));
    } catch(CannotHaveMoreDeputies $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotHaveMoreDeputies"));
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionDemote($id) {
    try{
      $this->model->demote($id);
      $this->flashMessage($this->translator->translate("messages.guild.demoted"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuild $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotDemoteHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDemoteHigherRanks"));
    } catch(CannotDemoteLowestRankException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotDemoteLowestRank"));
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionKick($id) {
    try {
      $this->model->kick($id);
      $this->flashMessage($this->translator->translate("messages.guild.kicked"));
    } catch(NotInGuildException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.notInGuild"));
    } catch(MissingPermissionsException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.missingPermissions"));
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerDoesNotExist"));
    } catch(PlayerNotInGuild $e) {
      $this->flashMessage($this->translator->translate("errors.guild.playerNotInGuild"));
    } catch(CannotKickHigherRanksException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotKickHigherRanks"));
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionDescription() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotChangeDescription"));
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  /**
   * Creates form for changing guild's description
   *
   * @return \Nette\Application\UI\Form
  */
  protected function createComponentGuildDescriptionForm(GuildDescriptionFormFactory $factory) {
    $form = $factory->create();
    $form->onSuccess[] = function() {
      $this->flashMessage($this->translator->translate("messages.guild.descriptionChanged"));
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  /**
   * @return void
   */
  function actionApplications() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "invite")) {
      $this->flashMessage($this->translator->translate("errors.guild.cannotManageApps"));
      $this->redirect("Guild:");
    }
  }
  
  /**
   * @return void
   */
  function renderApplications() {
    $this->template->apps = $this->model->showApplications($this->user->identity->guild);
  }
}
?>