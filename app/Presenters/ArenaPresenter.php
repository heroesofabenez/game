<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Arena;

/**
 * Presenter Arena
 *
 * @author Jakub Konečný
 */
class ArenaPresenter extends BasePresenter {
  /** @var int */
  protected $min_level = 3;
  
  public function renderChampion(int $id): void {
    $this->template->id = $id;
  }
  
  protected function createComponentArenaPVE(Arena\IArenaPVEControlFactory $factory) {
    return $factory->create();
  }
  
  protected function createComponentArenaPVP(Arena\IArenaPVPControlFactory $factory) {
    return $factory->create();
  }
}
?>