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
    $this->flashMessage("You are already in guild.");
    $char = $this->db->table("characters")->get($this->user->id);
    if($char->guild > 0) $this->forward("default");
  }
  
  function actionDefault() {
    $char = $this->db->table("characters")->get($this->user->id);
    if($char->guild == 0) $this->forward("noguild");
  }
  
  /**
   * @param int $id id of guild
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
   * Create form for creating guild
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
   * @todo implement :P
   * @param Nette\Application\UI\Form $form Sent form
   * @param  Nette\Utils\ArrayHash $values Array vith values
   * @return void
   */
  function createGuildFormSucceeded(UI\Form $form, $values) {
    $this->flashMessage("Guild created.");
    $this->redirect("Guild:");
  }
  
  function actionCreate() {
    $this->inGuild();
  }
  
  function actionJoin() {
    $this->inGuild();
  }
  
  function renderJoin() {
    $this->template->guilds = GuildModel::listOfGuilds($this->db);
  }
  
  function actionPromote($id) {
    $this->flashMessage("Member promoted.");
    $this->redirect("Guild:");
  }
  
  function actionDemote($id) {
    $this->flashMessage("Member demoted.");
    $this->redirect("Guild:");
  }
  
  function actionKick($id) {
    $this->flashMessage("Member kicked.");
    $this->redirect("Guild:");
  }
}
?>