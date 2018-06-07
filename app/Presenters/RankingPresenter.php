<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Ranking;

/**
 * Presenter Ranking
 *
 * @author Jakub Konečný
 */
final class RankingPresenter extends BasePresenter {
  protected const ITEMS_PER_PAGE = 15;
  /** @var \Nette\Utils\Paginator */
  protected $paginator;
  
  /**
   * Set up paginator
   */
  protected function startup(): void {
    parent::startup();
    $this->paginator = new \Nette\Utils\Paginator();
    $this->paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
    $this->paginator->setPage($this->getParameter("page"));
  }
  
  /**
   * Use just one template for this presenter
   */
  public function formatTemplateFiles() {
    return [__DIR__ . "/../templates/Ranking.@layout.latte"];
  }
  
  public function actionCharacters(int $page = 1): void {
    $this->template->title = $this->translator->translate("texts.ranking.charactersTitle");
    $this->template->ranking = "charactersRanking";
  }
  
  public function actionGuilds(int $page = 1): void {
    $this->template->title = $this->translator->translate("texts.ranking.guildsTitle");
    $this->template->ranking = "guildsRanking";
  }
  
  public function createComponentCharactersRanking(Ranking\ICharactersRankingControlFactory $factory): Ranking\CharactersRankingControl {
    $component = $factory->create();
    $component->paginator = $this->paginator;
    return $component;
  }
  
  public function createComponentGuildsRanking(Ranking\IGuildsRankingControlFactory $factory): Ranking\GuildsRankingControl {
    return $factory->create();
  }
}
?>