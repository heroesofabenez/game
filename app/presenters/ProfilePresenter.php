<?php
class ProfilePresenter extends Nette\Application\UI\Presenter {
  function renderView($id) {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $data = Profile::view($id, $db);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>