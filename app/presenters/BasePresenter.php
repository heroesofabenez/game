<?php
abstract class BasePresenter extends Nette\Application\UI\Presenter {
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
    if(is_a($this->presenter, "CharacterPresenter") AND $uid == -1) return;
    if(is_a($this->presenter, "CharacterPresenter") AND $uid > 0) $this->redirect(301, "Homepage:default");;
    switch($uid) {
case -1:
  $this->redirect(302, "Character:create");
  break;
case 0:
  //$this->redirectUrl("http://heroesofabenez.tk/");
    }
  }
}
