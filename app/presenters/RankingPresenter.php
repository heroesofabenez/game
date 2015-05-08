<?php
class RankingPresenter extends BasePresenter {
  function renderDefault() {
    $this->template->characters = Ranking::characters($this->db);
  }
  
  function renderGuilds() {
    $this->template->guilds = Ranking::guilds($this->db);
  }
}
?>
