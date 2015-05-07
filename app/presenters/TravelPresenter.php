<?php
class TravelPresenter extends Nette\Application\UI\Presenter {
  public function renderDefault($location) {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>