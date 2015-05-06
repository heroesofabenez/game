<?php
class GuildPresenter extends Nette\Application\UI\Presenter {
  function renderDefault() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
  }
  
  function renderView($id) {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $data = Guild::view($id, $db);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  function renderCreate() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
  }
  
  function renderJoin() {
    $this->template->site_name = $this->context->parameters["application"]["siteName"];
    $this->template->base_url = $this->context->parameters["application"]["baseUrl"];
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    $this->template->guilds = Guild::join($db);
  }
}
?>