<?php
class ProfilePresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $this->context->getService("user")->login();
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
  }
  
  function beforeRender() {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  function actionDefault() {
    $this->forward("view", $this->user->id);
  }
  
  function renderView($id) {
    $data = Profile::view($id, $db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>