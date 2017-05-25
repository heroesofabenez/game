<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Combat
 *
 * @author Jakub Konečný
 */
class CombatPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\CombatLogManager @autowire */
  protected $log;
  
  /**
   * @param int $id
   * @return void
   */
  function actionView(int $id): void {
    $combat = $this->log->read($id);
    if(is_null($combat)) {
      $this->forward("notfound");
    }
    $this->template->log = $combat->text;
  }
}
?>