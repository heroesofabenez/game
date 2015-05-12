<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

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
  function renderDefault($page) {
    $this->template->characters = HOA\Ranking::characters($this->db);
  }
  
  /**
   * @todo do pagination and ordering
   * @return void
   */
  function renderGuilds($page) {
    $this->template->guilds = HOA\Ranking::guilds($this->db);
  }
}
?>
