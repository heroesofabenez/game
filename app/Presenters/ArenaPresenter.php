<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Arena;

/**
 * Presenter Arena
 *
 * @author Jakub Konečný
 */
final class ArenaPresenter extends BasePresenter {
  /** @var int */
  protected $minLevel = 3;
  /** @var Arena\IArenaPVEControlFactory */
  protected $arenaPVEFactory;
  /** @var Arena\IArenaPVPControlFactory */
  protected $arenaPVPFactory;

  public function injectArenaPVEFactory(Arena\IArenaPVEControlFactory $arenaPVEFactory): void {
    $this->arenaPVEFactory = $arenaPVEFactory;
  }

  public function injectArenaPVPFactory(Arena\IArenaPVPControlFactory $arenaPVPFactory): void {
    $this->arenaPVPFactory = $arenaPVPFactory;
  }

  protected function startup(): void {
    parent::startup();
    if($this->user->identity->level < $this->minLevel) {
      $this->flashMessage($this->translator->translate("errors.arena.lowLevel", $this->minLevel));
      $this->redirect("Homepage:");
    }
  }

  public function renderChampion(int $id): void {
    $this->template->id = $id;
  }
  
  protected function createComponentArenaPVE(): Arena\ArenaPVEControl {
    return $this->arenaPVEFactory->create();
  }
  
  protected function createComponentArenaPVP(): Arena\ArenaPVPControl {
    return $this->arenaPVPFactory->create();
  }
}
?>