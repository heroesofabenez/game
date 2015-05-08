<?php
class BasePresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $this->tryLogin();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  function getDb() {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    return $db;
  }
  
  function tryLogin() {
    $user =$this->context->getService("user");
    /*if(!$user->isLoggedIn())*/ $user->login();
    $uid = $this->user->identity->id;
    switch($uid) {
case -1:
  $this->redirect("Character:create");
  break;
case 0:
  //$this->redirect("http://heroesofabenez.tk/");
    }
  }
}
