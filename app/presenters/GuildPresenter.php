<?php
use Nette\Application\UI;

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
    $guild = GuildModel::getGuildId($this->db, $this->user->id);
    if($guild > 0) {
      $this->flashMessage("You are already in guild.");
      $this->forward("default");
    }
  }
  
  function actionDefault() {
    $guild = GuildModel::getGuildId($this->db, $this->user->id);
    if($guild == 0) $this->forward("noguild");
  }
  
  /**
   * @param int $id Id of guild to view
   * @return void
   */
  function renderView($id) {
    if($id == 0) $this->forward("notfound");
    $data = GuildModel::view($id, $this->db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * Creates form for creating guild
   * @return Nette\Application\UI\Form
   */
  protected function createComponentCreateGuildForm() {
    $form = new UI\Form;
    $form->addText("name", "Name:")
         ->setRequired("You have to enter name.")
         ->addRule(Nette\Forms\Form::MAX_LENGTH, "Name can have no more than 20 letters", 20);
    $form->addTextArea("description", "Description:")
         ->addRule(Nette\Forms\Form::MAX_LENGTH, "Description can have no more than 200 letters", 200);
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
    $result = GuildModel::create($data, $this->user->id, $this->db);
    if($result) {
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
    $result = GuildModel::sendApplication($id, $this->user->id, $this->db);
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
    $this->template->guilds = GuildModel::listOfGuilds($this->db);
    $apps = GuildModel::haveUnresolvedApplication($this->user->id, $this->db);
    if($apps) $this->flashMessage("You have an unresolved application.");
  }
  
  /**
   * @todo implement the function
   * @return void
   */
  function actionPromote($id) {
    $this->flashMessage("Member promoted.");
    $this->redirect("Guild:");
  }
  
  /**
   * @todo implement the function
   * @return void
   */
  function actionDemote($id) {
    $this->flashMessage("Member demoted.");
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