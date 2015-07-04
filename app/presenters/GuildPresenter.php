<?php
namespace HeroesofAbenez\Presenters;

use \Nette\Application\UI;

  /**
   * Presenter Guild
   * 
   * @author Jakub Konečný
   */
class GuildPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Guild $model
   */
  function __construct(\HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
  }
  
  /**
   * Redirect player to guild page if he is already in guild
   * 
   * @return void
   */
  function inGuild() {
    $guild = $this->user->identity->guild;
    if($guild > 0) {
      $this->flashMessage("You are already in guild.");
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
      if($warrning) { $this->flashMessage("You are not in guild."); }
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
    $guild = $this->model->guildData($this->user->identity->guild);
    foreach($guild as $key => $value) {
      $this->template->$key = $value;
    }
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
    $roles = $this->context->getService("model.permissions")->getRoles();
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
    $form = new UI\Form;
    $form->addText("name", "Name:")
         ->setRequired("You have to enter name.")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "Name can have no more than 20 letters", 20);
    $form->addTextArea("description", "Description:")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "Description can have no more than 200 letters", 200);
    $form->addSubmit("create", "Create");
    $form->onSuccess[] = array($this, "createGuildFormSucceeded");
    return $form;
  }
  
  /**
   * Handles creating guild
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createGuildFormSucceeded(UI\Form $form, $values) {
    $data = array(
      "name" => $values["name"], "description" => $values["description"]
    );
    $result = $this->model->create($data);
    if($result) {
      $this->user->logout();
      $this->flashMessage("Guild created.");
      $this->redirect("Guild:");
    } else {
      $this->flashMessage("Guild with this name already exists.");
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
    if($id == 0) return;
    $result = $this->model->sendApplication($id);
    if($result === TRUE) {
      $this->flashMessage("Application sent.");
      $this->redirect("Guild:");
    } elseif($result === FALSE) {
      $this->flashMessage("An error occured.");
      $this->redirect("Guild:");
    } else {
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
    if($apps) $this->flashMessage("You have an unresolved application.");
  }
  
  /**
   * @return void
  */
  function actionLeave() {
    $this->notInGuild();
    $result = $this->model->leave();
    if($result) {
      $this->flashMessage("You left guild.");
      $this->user->logout();
    } else {
      $this->flashMessage("Grandmaster cannot leave guild.");
      $this->redirect("Guild:");
    }
    $this->forward("default");
  }
  
  /**
   * @return void
  */     
  function actionManage() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage("You can't manage guild.");
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
      $this->flashMessage("You can't rename guild.");
      $this->redirect("Guild:");
    }
  }
  
  /**
   * @return void
   */
  function actionDissolve() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "dissolve")) {
      $this->flashMessage("You can't dissolve guild.");
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
    $form = new UI\Form;
    $form->addText("name", "Name:")
         ->addRule(\Nette\Forms\Form::EQUAL, "You entered wrong name.", $currentName);
    $form->addSubmit("dissolve", "Dissolve");
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
  function dissolveGuildFormSucceeded($form, $values) {
    $gid = $this->user->identity->guild;
    $result = $this->model->dissolve($gid);
    if($result) {
      $this->flashMessage("Guild dissolved.");
      $this->user->logout();
      $this->redirect("Guild:noguild");
    } else {
      $this->flashMessage("An error occured.");
    }
  }
  
  /**
   * Creates form for renaming guild
   *
   * @return \Nette\Application\UI\Form
  */
  protected function createComponentRenameGuildForm() {
    $currentName = $this->model->getGuildName($this->user->identity->guild);
    $form = new UI\Form;
    $form->addText("name", "New name:")
         ->addRule(\Nette\Forms\Form::MAX_LENGTH, "Name can have no more than 20 letters.", 20)
         ->setDefaultValue($currentName);
    $form->addSubmit("rename", "Rename");
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
  function renameGuildFormSucceeded($form, $values) {
    $gid = $this->user->identity->guild;
    $name = $values["name"];
    $result = $this->model->rename($gid, $name, $this->context);
    if($result) {
      $this->flashMessage("Guild renamed.");
      $this->redirect("Guild:");
    } else {
      $this->flashMessage("Guild with this name already exists.");
    }
  }
  
  /**
   * @return void
   */
  function actionPromote($id) {
    $result = $this->model->promote($id);
    switch($result) {
case 1:
  $this->flashMessage("Member promoted.");
  break;
case 2:
  $this->flashMessage("You aren't in a guild.");
  break;
case 3:
  $this->flashMessage("You don't have permissions for this.");
  break;
case 4:
  $this->flashMessage("Specified player doesn't exist.");
  break;
case 5:
  $this->flashMessage("Specified player isn't in your guild.");
  break;
case 6:
  $this->flashMessage("You can't promote members with same or higher ranks.");
  break;
case 7:
  $this->flashMessage("You can't promote members to deputy or grandmaster.");
  break;
case 8:
  $this->flashMessage("Guild can't have more than 1 deputy.");
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionDemote($id) {
    $result = $this->model->demote($id);
    switch($result) {
case 1:
  $this->flashMessage("Member demoted.");
  break;
case 2:
  $this->flashMessage("You aren't in a guild.");
  break;
case 3:
  $this->flashMessage("You don't have permissions for this.");
  break;
case 4:
  $this->flashMessage("Specified player doesn't exist.");
  break;
case 5:
  $this->flashMessage("Specified player isn't in your guild.");
  break;
case 6:
  $this->flashMessage("You can't demote members with same or higher ranks.");
  break;
case 7:
  $this->flashMessage("You can't demote members with the lowest rank.");
  break;
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionKick($id) {
    $result = $this->model->kick($id);
    switch($result) {
case 1:
  $this->flashMessage("Member kicked.");
  break;
case 2:
  $this->flashMessage("You aren't in a guild.");
  break;
case 3:
  $this->flashMessage("You don't have permissions for this.");
  break;
case 4:
  $this->flashMessage("Specified player doesn't exist.");
  break;
case 5:
  $this->flashMessage("Specified player isn't in your guild.");
  break;
case 6:
  $this->flashMessage("You can't kick members with same or higher rank.");
  break;
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionDescription() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "manage")) {
      $this->flashMessage("You can't change guild's description.");
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
    $form = new UI\Form;
    $guild = $this->model->guildData($this->user->identity->guild);
    $form->addTextArea("description", "New description:")
         ->setDefaultValue($guild->description);
    $form->addSubmit("change", "Change");
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
  function guildDescriptionFormSucceeded($form, $values) {
    $guild = $this->user->identity->guild;
    $description = $values["description"];
    $result = $this->model->changeDescription($guild, $description, $this->context);
    switch($result) {
  case 1:
    $this->flashMessage("Guild's description changed.");
    break;
  case 2:
    $this->flashMessage("Guild doesn't exist.");
    break;
  default:
    $this->flashMessage("An error occurred.");
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionApplications() {
    $this->notInGuild();
    if(!$this->user->isAllowed("guild", "invite")) {
      $this->flashMessage("You can't manage applications.");
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