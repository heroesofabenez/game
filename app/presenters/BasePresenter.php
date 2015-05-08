<?php
class BasePresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $user =$this->context->getService("user");
    if(!$user->isLoggedIn()) $user->login();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  function getDb() {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    return $db;
  }
  
}
