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
   */
  function startup(): void {
    parent::startup();
    $this->paginator = new \Nette\Utils\Paginator;
    $this->paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
    $this->paginator->setPage($this->getParameter("page"));
  }
  
  /**
   * Use just one template for this presenter
   */
  function formatTemplateFiles() {
    return [__DIR__ . "/../templates/Ranking.@layout.latte"];
  }
  
  function actionCharacters(int $page = 1): void {
    $this->template->title = "Ranking Characters";
    $this->template->ranking = "charactersRanking";
  }
  
  function actionGuilds(int $page = 1): void {
    $this->template->title = "Ranking Guilds";
    $this->template->ranking = "guildsRanking";
  }
  
  function createComponentCharactersRanking(Ranking\ICharactersRankingControlFactory $factory): Ranking\CharactersRankingControl {
    $component = $factory->create();
    $component->paginator = $this->paginator;
    return $component;
  }
  
  function createComponentGuildsRanking(Ranking\IGuildsRankingControlFactory $factory): Ranking\GuildsRankingControl {
    return $factory->create();
  }
}
?>