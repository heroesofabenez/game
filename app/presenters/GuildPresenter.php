<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;
use \Nette\Application\UI;

  /**
   * Presenter Guild
   * 
   * @author Jakub Konečný
   */
class GuildPresenter extends BasePresenter {
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
  */
  function notInGuild($warrning = true) {
    $guild = $this->user->identity->guild;
    if($guild == 0) {
      if($warrning) { $this->flashMessage("You are not in guild."); }
      $this->forward("noguild");
    }
  }
  
  function actionDefault() {
    $this->notInGuild(false);
  }
  
  /**
   * @param int $id Id of guild to view
   * @return void
   */
  function renderView($id) {
    if($id == 0) $this->forward("notfound");
    $data = HOA\GuildModel::view($id, $this->context);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
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
   * @todo do not allow creating guild with name which is already used
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createGuildFormSucceeded(UI\Form $form, $values) {
    $data = array(
      "name" => $values["name"], "description" => $values["description"]
    );
    $cache = $this->context->getService("caches.guilds");
    $result = HOA\GuildModel::create($data, $this->user->id, $this->db);
    if($result) {
      $cache->remove("guilds");
      $this->user->logout();
      $this->flashMessage("Guild created.");
      $this->redirect("Guild:");
    }
  }
  
  function actionCreate() {
    $this->inGuild();
  }
  
  /**
   * @param int $id Guild to join   
   * @return void
   */
  function actionJoin($id) {
    $this->inGuild();
    if($id == 0) return;
    $result = HOA\GuildModel::sendApplication($id, $this->user->id, $this->db);
    if($result === TRUE) {
      $this->flashMessage("Application sent.");
      $this->redirect("Guild:");
    } elseif($result === FALSE) {
      $this->flashMessage("Application sent.");
      $this->redirect("Guild:");
    } else {
      $this->forward("notfound");
    }
  }
  
  /**
   * @return void
   */
  function renderJoin() {
    $guilds = HOA\GuildModel::listOfGuilds($this->context);
    $this->template->guilds = $guilds;
    $apps = HOA\GuildModel::haveUnresolvedApplication($this->user->id, $this->db);
    if($apps) $this->flashMessage("You have an unresolved application.");
  }
  
  /**
   * @return void
  */
  function actionLeave() {
    $this->notInGuild();
    if($this->user->isInRole("grandmaster")) {
      $this->flashMessage("Grandmaster cannot leave guild.");
      $this->redirect("Guild:");
    } else {
      HOA\GuildModel::leave($this->db, $this->user->id);
      $this->flashMessage("You left guild.");
      $this->user->logout();
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
  function actionPromote($id) {
    $result = HOA\GuildModel::promote($id, $this->context);
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
  $this->flashMessage("You can't promote members to leader.");
  break;
    }
    $this->redirect("Guild:");
  }
  
  /**
   * @return void
   */
  function actionDemote($id) {
    $result = HOA\GuildModel::demote($id, $this->context);
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
   * @todo implement the function
   * @return void
   */
  function actionKick($id) {
    $this->flashMessage("Member kicked.");
    $this->redirect("Guild:");
  }
}
?>