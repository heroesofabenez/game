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
  
  protected function createComponentArenaPVE(Arena\IArenaPVEControlFactory $factory): Arena\ArenaPVEControl {
    return $factory->create();
  }
  
  protected function createComponentArenaPVP(Arena\IArenaPVPControlFactory $factory): Arena\ArenaPVPControl {
    return $factory->create();
  }
}
?>