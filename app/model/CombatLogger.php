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
    $this->actions[] = (string) $text . "<br>";
  }
  
  /**
   * @param int $round
   * @return void
   */
  function logNewRound($round) {
    $this->actions[] = "<h4>Round $round</h4>";
  }
  
  /**
   * @return string
   */
  function __toString() {
    $latte = new \Latte\Engine;
    $params = array(
      "team1" => $this->team1, "team2" => $this->team2, "actions" => $this->actions
    );
    $latte->setTempDirectory(APP_DIR . "/temp");
    return $latte->renderToString(APP_DIR . "/templates/CombatLog.latte", $params);
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