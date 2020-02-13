<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Forms\CustomGuildRankNamesFormFactory;
use HeroesofAbenez\Forms\DonateToGuildFormFactory;
use Nette\Application\UI\Form;
use HeroesofAbenez\Model\GuildNotFoundException;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\GrandmasterCannotLeaveGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotPromoteHigherRanksException;
use HeroesofAbenez\Model\CannotPromoteToGrandmasterException;
use HeroesofAbenez\Model\CannotHaveMoreDeputiesException;
use HeroesofAbenez\Model\CannotDemoteHigherRanksException;
use HeroesofAbenez\Model\CannotDemoteLowestRankException;
use HeroesofAbenez\Model\CannotKickHigherRanksException;
use HeroesofAbenez\Forms\CreateGuildFormFactory;
use HeroesofAbenez\Forms\RenameGuildFormFactory;
use HeroesofAbenez\Forms\GuildDescriptionFormFactory;
use HeroesofAbenez\Forms\DissolveGuildFormFactory;

  /**
   * Presenter Guild
   * 
   * @author Jakub Konečný
   */
final class GuildPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Guild $model;
  protected \HeroesofAbenez\Model\Permissions $permissionsModel;
  protected CreateGuildFormFactory $createGuildFormFactory;
  protected DissolveGuildFormFactory $dissolveGuildFormFactory;
  protected RenameGuildFormFactory $renameGuildFormFactory;
  protected GuildDescriptionFormFactory $guildDescriptionFormFactory;
  protected CustomGuildRankNamesFormFactory $customGuildRankNamesFormFactory;
  protected DonateToGuildFormFactory $donateToGuildFormFactory;
  
  public function __construct(\HeroesofAbenez\Model\Guild $model, \HeroesofAbenez\Model\Permissions $permissionsModel) {
    parent::__construct();
    $this->model = $model;
    $this->permissionsModel = $permissionsModel;
  }

  public function injectCreateGuildFormFactory(CreateGuildFormFactory $createGuildFormFactory): void {
    $this->createGuildFormFactory = $createGuildFormFactory;
  }

  public function injectDissolveGuildFormFactory(DissolveGuildFormFactory $dissolveGuildFormFactory): void {
    $this->dissolveGuildFormFactory = $dissolveGuildFormFactory;
  }

  public function injectRenameGuildFormFactory(RenameGuildFormFactory $renameGuildFormFactory): void {
    $this->renameGuildFormFactory = $renameGuildFormFactory;
  }

  public function injectGuildDescriptionFormFactory(GuildDescriptionFormFactory $guildDescriptionFormFactory): void {
    $this->guildDescriptionFormFactory = $guildDescriptionFormFactory;
  }

  public function injectCustomGuildRankNamesFormFactory(CustomGuildRankNamesFormFactory $customGuildRankNamesFormFactory): void {
    $this->customGuildRankNamesFormFactory = $customGuildRankNamesFormFactory;
  }

  public function injectDonateToGuildFormFactory(DonateToGuildFormFactory $donateToGuildFormFactory): void {
    $this->donateToGuildFormFactory = $donateToGuildFormFactory;
  }
  
  /**
   * Redirect player to guild page if he is already in guild
   */
  protected function inGuild(): void {
    $guild = $this->user->identity->guild;
    if($guild > 0) {
      $this->flashMessage("errors.guild.inGuild");
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
    if($guild === 0) {
      if($warning) {
        $this->flashMessage("errors.guild.notInGuild");
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

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderView(int $id): void {
    $data = $this->model->view($id);
    if($data === null) {
      throw new \Nette\Application\BadRequestException();
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
    $this->template->rankId = $this->permissionsModel->getRankId($this->user->roles[0]);
  }

  protected function createComponentCreateGuildForm(): Form {
    $form = $this->createGuildFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->reloadIdentity();
      $this->flashMessage("messages.guild.created");
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
    if($id === null) {
      return;
    }
    try {
      $this->model->sendApplication($id);
      $this->flashMessage("messages.guild.applicationSent");
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
      $this->flashMessage("messages.guild.unresolvedApplication");
    }
  }
  
  public function actionLeave(): void {
    $this->notInGuild();
    try {
      $this->model->leave();
      $this->flashMessage("messages.guild.left");
      $this->reloadIdentity();
      $this->forward("default");
    } catch(NotInGuildException $e) {
      $this->flashMessage("errors.guild.notInGuild");
      $this->redirect("Guild:");
    } catch(GrandmasterCannotLeaveGuildException $e) {
      $this->flashMessage("errors.guild.grandmasterCannotLeave");
      $this->redirect("Guild:");
    }
  }
  
  public function actionManage(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage("errors.guild.cannotManage");
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
      $this->flashMessage("errors.guild.cannotRename");
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  public function actionDissolve(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "dissolve")) {
      $this->flashMessage("errors.guild.cannotDissolve");
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }

  protected function createComponentDissolveGuildForm(): Form {
    $form = $this->dissolveGuildFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->flashMessage("messages.guild.dissolved");
      $this->reloadIdentity();
      $this->redirect("Guild:noguild");
    };
    return $form;
  }

  protected function createComponentRenameGuildForm(): Form {
    $form = $this->renameGuildFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->flashMessage("messages.guild.renamed");
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  public function actionPromote(int $id): void {
    try {
      $this->model->promote($id);
      $this->flashMessage("messages.guild.promoted");
      $this->redirect("Guild:members");
    } catch(NotInGuildException $e) {
      $this->flashMessage("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $this->flashMessage("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage("errors.guild.playerNotInGuild");
    } catch(CannotPromoteHigherRanksException $e) {
      $this->flashMessage("errors.guild.cannotPromoteHigherRanks");
    } catch(CannotPromoteToGrandmasterException $e) {
      $this->flashMessage("errors.guild.cannotPromoteToGrandmaster");
    } catch(CannotHaveMoreDeputiesException $e) {
      $this->flashMessage("errors.guild.cannotHaveMoreDeputies");
    }
    $this->redirect("Guild:");
  }
  
  public function actionDemote(int $id): void {
    try {
      $this->model->demote($id);
      $this->flashMessage("messages.guild.demoted");
      $this->redirect("Guild:members");
    } catch(NotInGuildException $e) {
      $this->flashMessage("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $this->flashMessage("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage("errors.guild.playerNotInGuild");
    } catch(CannotDemoteHigherRanksException $e) {
      $this->flashMessage("errors.guild.cannotDemoteHigherRanks");
    } catch(CannotDemoteLowestRankException $e) {
      $this->flashMessage("errors.guild.cannotDemoteLowestRank");
    }
    $this->redirect("Guild:");
  }
  
  public function actionKick(int $id): void {
    try {
      $this->model->kick($id);
      $this->flashMessage("messages.guild.kicked");
      $this->redirect("Guild:members");
    } catch(NotInGuildException $e) {
      $this->flashMessage("errors.guild.notInGuild");
    } catch(MissingPermissionsException $e) {
      $this->flashMessage("errors.guild.missingPermissions");
    } catch(PlayerNotFoundException $e) {
      $this->flashMessage("errors.guild.playerDoesNotExist");
    } catch(PlayerNotInGuildException $e) {
      $this->flashMessage("errors.guild.playerNotInGuild");
    } catch(CannotKickHigherRanksException $e) {
      $this->flashMessage("errors.guild.cannotKickHigherRanks");
    }
    $this->redirect("Guild:");
  }
  
  public function actionDescription(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage("errors.guild.cannotChangeDescription");
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }

  protected function createComponentGuildDescriptionForm(): Form {
    $form = $this->guildDescriptionFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->flashMessage("messages.guild.descriptionChanged");
      $this->redirect("Guild:");
    };
    return $form;
  }
  
  public function actionApplications(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "invite")) {
      $this->flashMessage("errors.guild.cannotManageApps");
      $this->redirect("Guild:");
    }
  }
  
  public function renderApplications(): void {
    $this->template->apps = $this->model->showApplications($this->user->identity->guild);
  }
  
  public function actionRankNames(): void {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "changeRankNames")) {
      $this->flashMessage("errors.guild.cannotChangeRankNames");
      $this->redirect("Guild:");
    }
    $this->template->haveForm = true;
  }
  
  protected function createComponentCustomGuildRankNamesForm(): Form {
    $form = $this->customGuildRankNamesFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->flashMessage("messages.guild.customRankNamesSet");
      $this->redirect("Guild:");
    };
    return $form;
  }

  public function actionDonate(): void {
    $this->notInGuild();
    $this->template->haveForm = true;
  }

  protected function createComponentDonateToGuildForm(): Form {
    $form = $this->donateToGuildFormFactory->create();
    $form->onSuccess[] = function(): void {
      $this->flashMessage("messages.guild.donationDone");
      $this->redirect("Guild:");
    };
    return $form;
  }
}
?>