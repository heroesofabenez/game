<?php
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
  
  /**
   * @param int $id
   * @return void
   */
  function actionChampion($id) {
    
  }
  
  protected function createComponentArenaPVE(Arena\ArenaPVEControlFactory $factory) {
    return $factory->create();
  }
  
  protected function createComponentArenaPVP(Arena\ArenaPVPControlFactory $factory) {
    return $factory->create();
  }
}
?>