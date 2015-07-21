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
    $this->paginator->setPage($this->getParameter("page"));
  }
  
  /**
   * Use just one template for this presenter
   * 
   * @return array
   */
  function formatTemplateFiles() {
    return array(APP_DIR . "/templates/Ranking.@layout.latte");
  }
  
  /**
   * @param int $page Page to show
   * @return void
   */
  function actionCharacters($page) {
    $this->template->title = "Ranking Characters";
    $this->template->ranking = "charactersRanking";
  }
  
  /**
   * @param int $page Page to show
   * @return void
   */
  function actionGuilds($page) {
    $this->template->title = "Ranking Guilds";
    $this->template->ranking = "guildsRanking";
  }
  
  /**
   * @return \HeroesofAbenez\Ranking\CharactersRankingControl
   */
  function createComponentCharactersRanking() {
    $component = $this->context->getService("ranking.characters");
    $component->paginator = $this->paginator;
    return $component;
  }
  
  /**
   * @return \HeroesofAbenez\Ranking\GuildsRankingControl
   */
  function createComponentGuildsRanking() {
    return $this->context->getService("ranking.guilds");
  }
}
?>