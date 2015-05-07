<?php
class HomepagePresenter extends Nette\Application\UI\Presenter {
  function renderDefault() {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
}
?>