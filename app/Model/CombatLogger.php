<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Character as CharacterEntity,
    HeroesofAbenez\Entities\CombatAction,
    HeroesofAbenez\Entities\Team,
    Nette\Bridges\ApplicationLatte\ILatteFactory;

/**
 * Combat log
 * 
 * @author Jakub Konečný
 * @property int $round Current round
 */
class CombatLogger implements \Countable, \IteratorAggregate {
  use \Nette\SmartObject;
  
  /** @var \Latte\Engine */
  protected $latte;
  /** @var Team First team */
  protected $team1;
  /** @var Team Second team */
  protected $team2;
  /** @var array */
  protected $actions = [];
  /** @var int */
  protected $round;
  
  /**
   * @param ILatteFactory $latteFactory
   */
  function __construct(ILatteFactory $latteFactory) {
    $this->latte = $latteFactory->create();
  }
  
  /**
   * Set teams
   * 
   * @param Team $team1
   * @param Team $team2
   * @return void
   */
  function setTeams(Team $team1, Team $team2) {
    if($this->team1) throw new ImmutableException("Teams has already been set.");
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
  /**
   * @return int
   */
  function getRound(): int {
    return $this->round;
  }
  
  /**
   * @param int $round
   */
  function setRound(int $round) {
    $this->round = $round;
  }
  
  /**
   * Adds new entry
   * 
   * @param string $action
   * @param bool $result
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @param int $amount
   * @param string $name
   * @return void
   */
  function log($action, $result, CharacterEntity $character1, CharacterEntity $character2, int $amount = 0, string $name = "") {
    $this->actions[$this->round][] = new CombatAction($action, $result, $character1, $character2, $amount, $name);
  }
  
  /**
   * Adds text entry
   * 
   * @param string $text
   * @return void
   */
  function logText(string $text) {
    $this->actions[$this->round][] = $text;
  }
  
  /**
   * @return string
   */
  function __toString(): string {
    $params = [
      "team1" => $this->team1, "team2" => $this->team2, "actions" => $this->actions
    ];
    $this->latte->setTempDirectory(WWW_DIR . "/temp/combats");
    return $this->latte->renderToString(APP_DIR . "/templates/CombatLog.latte", $params);
  }
  
  /**
   * @return int
   */
  function count(): int {
    return count($this->actions);
  }
  
  /**
   * @return \ArrayIterator
   */
  function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->actions);
  }
}
?>