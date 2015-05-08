<?php
class RankingPresenter extends Nette\Application\UI\Presenter {
  function startup() {
    parent::startup();
    $user =$this->context->getService("user");
    if(!$user->isLoggedIn()) $user->login();
  }
  
  function beforeRender() {
    $this->template->server = $this->context->parameters["application"]["server"];
  }
  
  function getDb() {
    $db = $this->context->getService("database.default.context");
    $db->structure->rebuild();
    return $db;
  }
  
  function renderDefault() {
    $this->template->characters = Ranking::characters($this->db);
  }
  
  function renderGuilds() {
    $this->template->guilds = Ranking::guilds($this->db);
  }
}
?>
