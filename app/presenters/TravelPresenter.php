<?php
class TravelPresenter extends Nette\Application\UI\Presenter {
  function renderDefault($location) {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>