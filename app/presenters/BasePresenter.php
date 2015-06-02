<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Parent of all presenters
   * 
   * @author Jakub Konečný
   */
abstract class BasePresenter extends \Nette\Application\UI\Presenter {
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
   * Return real user's id
   * @return int
   */
  static function getRealId() {
    $dev_servers = array("localhost", "kobliha");
    if(in_array($_SERVER["SERVER_NAME"], $dev_servers)) {
      $uid = 1;
    } else {
      $ch = curl_init("http://heroesofabenez.tk/auth.php");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $uid = curl_exec($ch);
      curl_close($ch);
    }
    return $uid;
  }
  
  /**
   * Try to login the user
   * @return void
   */
  function tryLogin() {
    if(!$this->user->isLoggedIn()) $this->user->login();
    $uid = $this->user->id;
    if(is_a($this->presenter, "\HeroesofAbenez\Presenters\CharacterPresenter") AND $uid == -1) return;
    if(is_a($this->presenter, "\HeroesofAbenez\Presenters\CharacterPresenter") AND $this->user->identity->stage == NULL) return;
    if(is_a($this->presenter, "\HeroesofAbenez\Presenters\CharacterPresenter") AND $uid > 0) $this->redirect(301, "Homepage:default");
    if(is_a($this->presenter, "\HeroesofAbenez\Presenters\IntroPresenter") AND $this->user->identity->stage == NULL) return;
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
