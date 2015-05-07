<?php
class HomepagePresenter extends Nette\Application\UI\Presenter {
  public function renderDefault() {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>