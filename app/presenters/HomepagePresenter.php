<?php
class HomepagePresenter extends Nette\Application\UI\Presenter {
  function renderDefault() {
    $this->context->getService("user")->login();
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>