<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
class JournalPresenter extends BasePresenter {
  /**
   * @return void
   */
  function renderDefault() {
    $stats = HOA\Journal::basic($this->context);
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>