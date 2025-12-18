<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Arena;

/**
 * Presenter Arena
 *
 * @author Jakub Konečný
 */
final class ArenaPresenter extends BasePresenter
{
    private int $minLevel = 3;
    private Arena\ArenaPVEControlFactory $arenaPVEFactory;
    private Arena\ArenaPVPControlFactory $arenaPVPFactory;

    public function injectArenaPVEFactory(Arena\ArenaPVEControlFactory $arenaPVEFactory): void
    {
        $this->arenaPVEFactory = $arenaPVEFactory;
    }

    public function injectArenaPVPFactory(Arena\ArenaPVPControlFactory $arenaPVPFactory): void
    {
        $this->arenaPVPFactory = $arenaPVPFactory;
    }

    protected function startup(): void
    {
        parent::startup();
        if ($this->user->identity->level < $this->minLevel) {
            $this->flashMessage($this->translator->translate("errors.arena.lowLevel", $this->minLevel));
            $this->redirect("Homepage:");
        }
    }

    public function renderChampion(int $id): void
    {
        $this->template->id = $id;
    }

    protected function createComponentArenaPVE(): Arena\ArenaPVEControl
    {
        return $this->arenaPVEFactory->create();
    }

    protected function createComponentArenaPVP(): Arena\ArenaPVPControl
    {
        return $this->arenaPVPFactory->create();
    }
}
