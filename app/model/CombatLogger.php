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
  /** @var \HeroesofAbenez\Entities\Team First team */
  protected $team1;
  /** @var \HeroesofAbenez\Entities\Team Second team */
  protected $team2;
  /** @var array */
  protected $actions = array();
  /** @var int */
  protected $pos;
  
  function __construct(Team $team1, Team $team2) {
    $this->team1 = $team1;
    $this->team2 = $team2;
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
  
  /**
   * @return string
   */
  function __toString() {
    $output = "{$this->team1->name}:<br>\n";
    foreach($this->team1 as $member) {
      $output .= "$member->name: level $member->level<br>\n";
    }
    $output .= "<br>\n";
    $output .= "{$this->team2->name}:<br>\n";
    foreach($this->team2 as $member) {
      $output .= "$member->name: level $member->level<br>\n";
    }
    $output .= "<br>\n";
    foreach($this->actions as $text) {
      $output .= "$text<br>\n";
    }
    return $output;
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