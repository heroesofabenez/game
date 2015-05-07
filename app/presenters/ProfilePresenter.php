<?php
class ProfilePresenter extends Nette\Application\UI\Presenter {
  function renderView($id) {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $data = Profile::view($id, $db);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>