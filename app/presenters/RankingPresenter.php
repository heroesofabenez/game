<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

  /**
   * Presenter Ranking
   * 
   * @author Jakub Konečný
   */
class RankingPresenter extends BasePresenter {
  const ITEMS_PER_PAGE = 15;
  /**
   * @todo do pagination
   * @return void
   */
  function renderDefault($page) {
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
    $paginator->setPage($page);
    $this->template->characters = HOA\Ranking::characters($this->db, $paginator);
    $this->template->paginator = $paginator;
  }
  
  /**
   * @todo do pagination and ordering
   * @return void
   */
  function renderGuilds($page) {
    $guilds = HOA\Ranking::guilds($this->context);
    $this->template->guilds = $guilds;
  }
}
?>
