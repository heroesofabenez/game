<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Ranking
   * 
   * @author Jakub Konečný
   */
class RankingPresenter extends BasePresenter {
  const ITEMS_PER_PAGE = 15;
  /** @var \Nette\Utils\Paginator */
  protected $paginator;
  /** @var \HeroesofAbenez\Ranking */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Ranking $model
   */
  function __construct(\HeroesofAbenez\Ranking $model) {
    $this->model = $model;
  }
  
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
    $this->template->characters = $this->model->characters($this->paginator);
    $this->template->paginator = $this->paginator;
  }
  
  function createComponentGuildsRanking() {
    return $this->context->getService("ranking.guilds");
  }
}
?>