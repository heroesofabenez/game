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
   * @param int $page Page to show
   * @return void
   */
  function renderDefault($page) {
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
    $paginator->setPage($page);
    $this->template->characters = HOA\Ranking::characters($this->context, $paginator);
    $this->template->paginator = $paginator;
  }
  
  /**
   * @todo do pagination and ordering
   * @param int $id Page to show
   * @return void
   */
  function renderGuilds($id) {
    $guilds = HOA\Ranking::guilds($this->context);
    $this->template->guilds = $guilds;
  }
}
?>
