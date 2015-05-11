<?php
  /**
   * Presenter Ranking
   * 
   * @author Jakub Konečný
   */
class RankingPresenter extends BasePresenter {
  /**
   * @todo do pagination
   * @return void
   */
  function renderDefault() {
    $this->template->characters = Ranking::characters($this->db);
  }
  
  /**
   * @todo do pagination and ordering
   * @return void
   */
  function renderGuilds() {
    $this->template->guilds = Ranking::guilds($this->db);
  }
}
?>
