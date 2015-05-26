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
  /** @var \Nette\Utils\Paginator */
  protected $paginator;
  
  /**
   * Set up paginator
   * 
   * @return void
   */
  function startup() {
    parent::startup();
    $this->paginator = new \Nette\Utils\Paginator;
    $this->paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
  }
  
  /**
   * @param int $page Page to show
   * @return void
   */
  function renderCharacters($page) {
    $this->paginator->setPage($page);
    $this->template->characters = HOA\Ranking::characters($this->context, $this->paginator);
    $this->template->paginator = $this->paginator;
  }
  
  /**
   * @todo do pagination and ordering
   * @param int $page Page to show
   * @return void
   */
  function renderGuilds($page) {
    $this->paginator->setPage($page);
    $guilds = HOA\Ranking::guilds($this->context);
    $this->template->guilds = $guilds;
    $this->template->paginator = $this->paginator;
  }
}
?>
