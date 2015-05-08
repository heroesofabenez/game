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
    /*if(!$user->isLoggedIn())*/ $identity = $user->login();
    $uid = $this->user->identity->id;
    switch($uid) {
case -1:
  echo "You have no character on this server. Create one now.";
  $this->redirect("Character:create");
  break;
case 0:
  echo "You are not logged in. Go to the website, login there and return.";
  //$this->redirect("http://heroesofabenez.tk/");
    }
  }
}
