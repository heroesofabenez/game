<?php
use Nette\Application\UI;

class GuildPresenter extends UI\Presenter {
  function renderDefault() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $this->forward("noguild");
  }
  
  function renderNoguild() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
  }
  
  function renderView($id) {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $data = Guild::view($id, $db);
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
  
  function renderCreate() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
  }
  
  function renderJoin() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $this->template->guilds = Guild::join($db);
  }
}
?>