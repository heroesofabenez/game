<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Character as CharacterEntity,
    HeroesofAbenez\Entities\CombatAction,
    HeroesofAbenez\Entities\Team;

/**
 * Combat log
 * 
 * @author Jakub Konečný
 */
class CombatLogger extends \Nette\Object implements \Iterator {
  /** @var array */
  protected $actions = array();
  /** @var int */
  protected $pos;
  
  /**
   * Print participants of the combat
   * 
   * @param Team $team1
   * @param Team $team2
   * @return void
   */
  function openLog(Team $team1, Team $team2) {
    $this->logText("$team1->name:");
    foreach($team1 as $member) {
      $this->logText("$member->name: level $member->level");
    }
    $this->logText("");
    $this->logText("$team2->name:");
    foreach($team2 as $member) {
      $this->logText("$member->name: level $member->level");
    }
    $this->logText("");
  }
  
  /**
   * Adds new entry
   * 
   * @param string $action
   * @param bool $result
   * @param \HeroesofAbenez\Entities\Character $character1
   * @param \HeroesofAbenez\Entities\Character $character2
   * @param int $amount
   * @param string $name
   */
  function log($action, $result, CharacterEntity $character1, CharacterEntity $character2, $amount = 0, $name = "") {
    $this->actions[] = new CombatAction($action, $result, $character1, $character2, $amount, $name);
  }
  
  /**
   * Adds text entry
   * 
   * @param string $text
   * @return void
   */
  function logText($text) {
    $this->actions[] = (string) $text;
  }
  
  function rewind() {
    $this->pos = 0;
  }
  
  function current() {
    return $this->actions[$this->pos];
  }
  
  function key() {
    return $this->pos;
  }
  
  function next() {
    ++$this->pos;
  }
  
  function valid() {
    return isset($this->actions[$this->pos]);
  }
}
?>