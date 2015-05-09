<?php
class RankingPresenter extends BasePresenter {
  /**
   * Presenter Ranking
   * 
   * @author Jakub Konečný
   */
  
  function renderDefault() {
    $this->template->characters = Ranking::characters($this->db);
  }
  
  function renderGuilds() {
    $this->template->guilds = Ranking::guilds($this->db);
  }
}
?>
