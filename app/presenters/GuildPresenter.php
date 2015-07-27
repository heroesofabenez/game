<?php
namespace HeroesofAbenez\Presenters;

use \Nette\Application\UI\Form,
    \Nette\Application\ForbiddenRequestException;

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
    $guild = $this->model->guildData($this->user->identity->guild)->__toArray();
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
    $this->template->members = $this->model->guildMembers($this->user->identity->guild);
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
   * @return \Nette\Application\UI\Form
   */
  protected function createComponentCreateGuildForm() {
    $form = new Form;
    $form->translator = $this->translator;
    $form->addText("name", "forms.createGuild.nameField.label")
         ->setRequired("forms.createGuild.nameField.empty")
         ->addRule(Form::MAX_LENGTH, "forms.createGuild.nameField.error", 20);
    $form->addTextArea("description", "forms.createGuild.descriptionField.label")
         ->addRule(Form::MAX_LENGTH, "forms.createGuild.descriptionField.error", 200);
    $form->addSubmit("create", "forms.createGuild.createButton.label");
    $form->onSuccess[] = array($this, "createGuildFormSucceeded");
    return $form;
  }
  
  /**
   * Handles creating guild
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createGuildFormSucceeded(Form $form, $values) {
    $data = array(
      "name" => $values["name"], "description" => $values["description"]
    );
    try {
      $this->model->create($data);
      $this->user->logout();
      $this->flashMessage($this->translator->translate("messages.guild.created"));
      $this->redirect("Guild:");
    } catch(ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
    }
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
    } catch (Exception $e) {
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
    } catch(ForbiddenRequestException $e) {
      if($e->getCode() === 202) {
        $this->flashMessage($e->getMessage());
        $this->redirect("Guild:");
      }
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
   * @return \Nette\Application\UI\Form
  */
  protected function createComponentDissolveGuildForm() {
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form = new Form;
    $form->translator = $this->translator;
    $form->addText("name", "forms.dissolveGuild.nameField.label")
         ->addRule(Form::EQUAL, "forms.dissolveGuild.nameField.error", $currentName);
    $form->addSubmit("dissolve", "forms.dissolveGuild.dissolveButton.label");
    $form->onSuccess[] = array($this, "dissolveGuildFormSucceeded");
    return $form;
  }
  
  /**
   * Handles dissolving guild
   *
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
  */
  function dissolveGuildFormSucceeded(Form $form, $values) {
    $gid = $this->user->identity->guild;
    $this->model->dissolve($gid);
    $this->flashMessage($this->translator->translate("messages.guild.dissolved"));
    $this->user->logout();
    $this->redirect("Guild:noguild");
  }
  
  /**
   * Creates form for renaming guild
   *
   * @return \Nette\Application\UI\Form
  */
  protected function createComponentRenameGuildForm() {
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form = new Form;
    $form->translator = $this->translator;
    $form->addText("name", "forms.renameGuild.nameField.label")
         ->addRule(Form::MAX_LENGTH, "forms.renameGuild.nameField.error", 20)
         ->setDefaultValue($currentName);
    $form->addSubmit("rename", "forms.renameGuild.renameButton.label");
    $form->onSuccess[] = array($this, "renameGuildFormSucceeded");
    return $form;
  }
  
  /**
   * Handles renaming guild
   *
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
  */
  function renameGuildFormSucceeded(Form $form, $values) {
    $gid = $this->user->identity->guild;
    $name = $values["name"];
    try {
      $this->model->rename($gid, $name);
      $this->flashMessage($this->translator->translate("messages.guild.renamed"));
      $this->redirect("Guild:");
    } catch(\Nette\Application\ApplicationException $e) {
      $this->flashMessage($e->getMessage());
    }
  }
  
  /**
   * @return void
   */
  function actionPromote($id) {
    try{
      $this->model->promote($id);
      $this->flashMessage($this->translator->translate("messages.guild.promoted"));
    } catch(ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
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
    } catch(ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
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
    } catch(ForbiddenRequestException $e) {
      $this->flashMessage($e->getMessage());
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
  protected function createComponentGuildDescriptionForm() {
    $form = new Form;
    $form->translator = $this->translator;
    $guild = $this->model->guildData($this->user->identity->guild);
    $form->addTextArea("description", "forms.guildDescription.descriptionField.label")
         ->setDefaultValue($guild->description);
    $form->addSubmit("change", "forms.guildDescription.changeButton.label");
    $form->onSuccess[] = array($this, "guildDescriptionFormSucceeded");
    return $form;
  }
  /**
   * Handles chaning guild's description
   *
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
  */
  function guildDescriptionFormSucceeded(Form $form, $values) {
    $guild = $this->user->identity->guild;
    $description = $values["description"];
    try {
      $this->model->changeDescription($guild, $description);
      $this->flashMessage($this->translator->translate("messages.guild.descriptionChanged"));
    } catch(\Nette\Application\BadRequestException $e) {
      $this->flashMessage($this->translator->translate("errors.guild.doesNotExist"));
    }
    $this->redirect("Guild:");
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