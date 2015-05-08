<?php
use Nette\Application\UI;

class GuildPresenter extends BasePresenter {
  function inGuild() {
    $this->flashMessage("You are already in guild.");
    $char = $this->db->table("characters")->get($this->user->id);
    if($char->guild > 0) $this->forward("default");
  }
  
  function actionDefault() {
    $char = $this->db->table("characters")->get($this->user->id);
    if($char->guild == 0) $this->forward("noguild");
  }
  
  function renderView($id) {
    if($id == 0) $this->forward("notfound");
    $data = Guild::view($id, $this->db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
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
  
  public function createGuildFormSucceeded(UI\Form $form, $values) {
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
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $this->template->guilds = Guild::join($this->db);
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