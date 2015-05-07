<?php
use Nette\Application\UI;

class GuildPresenter extends UI\Presenter {
  function beforeRender() {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  function actionDefault() {
    $this->forward("noguild");
  }
  
  function renderDefault() { }
  
  //function renderNoguild() { }
  
  function renderView($id) {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    if($id == 0) $this->forward("notfound");
    $data = Guild::view($id, $db);
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
  
  function renderCreate() { }
  
  function renderJoin() {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $this->template->guilds = Guild::join($db);
  }
}
?>