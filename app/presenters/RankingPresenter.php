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
  function actionCharacters($page) {
    $this->paginator->setPage($page);
  }
  
  function createComponentCharactersRanking() {
    $component = $this->context->getService("ranking.characters");
    $component->paginator = $this->paginator;
    return $component;
  }
  
  function createComponentGuildsRanking() {
    return $this->context->getService("ranking.guilds");
  }
}
?>