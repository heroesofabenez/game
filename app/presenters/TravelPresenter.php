<?php
class TravelPresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $user =$this->context->getService("user");
    if(!$user->isLoggedIn()) $user->login();
  }
  
  function renderDefault($location) {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>