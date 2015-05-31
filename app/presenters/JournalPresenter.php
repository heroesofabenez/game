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
  
  /**
   * @return void
   */
  function renderInventory() {
    $inventory = HOA\Journal::inventory($this->context);
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function renderQuests() {
    $quests = array();
    $this->template->quests = $quests;
  }
  
  /**
   * @return void
   */
  function renderPets() {
    $pets = HOA\Journal::pets($this->context);
    $this->template->pets = $pets;
  }
}
?>