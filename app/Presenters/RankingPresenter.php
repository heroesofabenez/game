<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Ranking;

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
  function startup(): void {
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
    return [__DIR__ . "/../templates/Ranking.@layout.latte"];
  }
  
  /**
   * @param int $page Page to show
   * @return void
   */
  function actionCharacters(int $page = 1): void {
    $this->template->title = "Ranking Characters";
    $this->template->ranking = "charactersRanking";
  }
  
  /**
   * @param int $page Page to show
   * @return void
   */
  function actionGuilds(int $page = 1): void {
    $this->template->title = "Ranking Guilds";
    $this->template->ranking = "guildsRanking";
  }
  
  /**
   * @param Ranking\CharactersRankingControlFactory $factory
   * @return Ranking\CharactersRankingControl
   */
  function createComponentCharactersRanking(Ranking\CharactersRankingControlFactory $factory): Ranking\CharactersRankingControl {
    $component = $factory->create();
    $component->paginator = $this->paginator;
    return $component;
  }
  
  /**
   * @param Ranking\GuildsRankingControlFactory $factory
   * @return Ranking\GuildsRankingControl
   */
  function createComponentGuildsRanking(Ranking\GuildsRankingControlFactory $factory): Ranking\GuildsRankingControl {
    return $factory->create();
  }
}
?>