<?php
  /**
   * Parent of all presenters
   * 
   * @author Jakub Konečný
   * @property-read Nette\Database\Context $db Database context
   */
abstract class BasePresenter extends Nette\Application\UI\Presenter {
  /**
   * Login user and set server number for template
   * @return void
   */
  function startup() {
    parent::startup();
    $this->tryLogin();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  /**
   * Provides virtual variable db for all presenters
   * @return Nette\Database\Context database context
   */
  function getDb() {
    return $this->context->getService("database.default.context");
  }
  
  /**
   * Return real user's id
   * @return int
   */
  static function getRealId() {
    $dev_servers = array("localhost", "kobliha", "test.heroesofabenez.tk");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      $uid = 1;
    } else {
      define('WP_USE_THEMES', false);
      require( WWW_DIR . '/../wp-blog-header.php' );
      $uid = get_current_user_id();
    }
    return $uid;
  }
  
  /**
   * Try to login the user
   * @return void
   * @todo uncomment redirecting to website
   */
  function tryLogin() {
    $user =$this->context->getService("user");
    /*if(!$user->isLoggedIn())*/ $user->login();
    $uid = $this->user->identity->id;
    if(is_a($this->presenter, "CharacterPresenter") AND $uid == -1) return;
    if(is_a($this->presenter, "CharacterPresenter") AND $this->user->identity->stage == NULL) return;
    if(is_a($this->presenter, "CharacterPresenter") AND $uid > 0) $this->redirect(301, "Homepage:default");
    if(is_a($this->presenter, "IntroPresenter") AND $this->user->identity->stage == NULL) return;
    switch($uid) {
case -1:
  $this->redirect(302, "Character:create");
  break;
case 0:
  //$this->redirectUrl("http://heroesofabenez.tk/");
    }
    if($this->user->identity->stage == NULL) $this->redirect(302, "Intro:default");
  }
}
