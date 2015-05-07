<?php
class TravelPresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $this->context->getService("user")->login();
  }
  
  function renderDefault($location) {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>