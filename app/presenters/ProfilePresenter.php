<?php
class ProfilePresenter extends Nette\Application\UI\Presenter {
  function beforeRender() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
  }
  
  function actionDefault() {
    // todo: pass id of current character
    $this->forward("view", 1);
  }
  
  function renderView($id) {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $data = Profile::view($id, $db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>