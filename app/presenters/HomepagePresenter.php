<?php
class HomepagePresenter extends Nette\Application\UI\Presenter {
  function renderDefault() {
    $user =$this->context->getService("user");
    if(!$user->isLoggedIn()) $user->login();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>