<?php
class HomepagePresenter extends Nette\Application\UI\Presenter {
  public function renderDefault() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
  }
}
?>