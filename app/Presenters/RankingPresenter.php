<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Ranking;

/**
 * Presenter Ranking
 *
 * @author Jakub Konečný
 */
final class RankingPresenter extends BasePresenter
{
    private const int ITEMS_PER_PAGE = 15;
    private \Nette\Utils\Paginator $paginator;
    private Ranking\CharactersRankingControlFactory $charactersRankingFactory;
    private Ranking\GuildsRankingControlFactory $guildRankingFactory;

    public function injectCharactersRankingFactory(
        Ranking\CharactersRankingControlFactory $charactersRankingFactory
    ): void {
        $this->charactersRankingFactory = $charactersRankingFactory;
    }

    public function injectGuildRankingFactory(Ranking\GuildsRankingControlFactory $guildRankingFactory): void
    {
        $this->guildRankingFactory = $guildRankingFactory;
    }

    /**
     * Set up paginator
     */
    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->paginator = new \Nette\Utils\Paginator();
        $this->paginator->setItemsPerPage(self::ITEMS_PER_PAGE);
        $this->paginator->setPage((int) $this->getParameter("page"));
    }

    /**
     * Use just one template for this presenter
     */
    public function formatTemplateFiles(): array
    {
        return [__DIR__ . "/../templates/Ranking.@layout.latte"];
    }

    public function actionCharacters(int $page = 1): void
    {
        $this->template->title = $this->translator->translate("texts.ranking.charactersTitle");
        $this->template->ranking = "charactersRanking";
    }

    public function actionGuilds(int $page = 1): void
    {
        $this->template->title = $this->translator->translate("texts.ranking.guildsTitle");
        $this->template->ranking = "guildsRanking";
    }

    public function createComponentCharactersRanking(): Ranking\CharactersRankingControl
    {
        $component = $this->charactersRankingFactory->create();
        $component->paginator = $this->paginator;
        return $component;
    }

    public function createComponentGuildsRanking(): Ranking\GuildsRankingControl
    {
        return $this->guildRankingFactory->create();
    }
}
