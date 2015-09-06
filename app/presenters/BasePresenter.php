<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Parent of all presenters
   * 
   * @author Jakub Konečný
   */
abstract class BasePresenter extends \Nette\Application\UI\Presenter {
  /** @var \Kdyby\Translation\Translator @autowire */
  protected $translator;
  
  use \Kdyby\Autowired\AutowireProperties;
  use \Kdyby\Autowired\AutowireComponentFactories;
  
  /**
   * Login user and set server number for template
   * 
   * @return void
   */
  function startup() {
    parent::startup();
    $this->tryLogin();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  /**
   * Try to login the user
   * 
   * @return void
   */
  function tryLogin() {
    if(!$this->user->isLoggedIn()) $this->user->login();
    $uid = $this->user->id;
    if($this instanceof CharacterPresenter AND $uid == -1) return;
    if($this instanceof CharacterPresenter AND $this->user->identity->stage == NULL) return;
    if($this instanceof CharacterPresenter AND $uid > 0) $this->redirect(301, "Homepage:default");
    if($this instanceof IntroPresenter AND $this->user->identity->stage == NULL) return;
    switch($uid) {
case -1:
  $this->redirect(302, "Character:create");
  break;
case 0:
  $this->redirectUrl("http://heroesofabenez.tk/");
    }
    if($this->user->identity->stage == NULL) $this->redirect(302, "Intro:default");
  }
}
?>