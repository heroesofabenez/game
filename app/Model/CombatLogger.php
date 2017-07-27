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
  
  public function __construct(ILatteFactory $latteFactory) {
    $this->latte = $latteFactory->create();
  }
  
  /**
   * Set teams
   */
  public function setTeams(Team $team1, Team $team2): void {
    if($this->team1) {
      throw new ImmutableException("Teams has already been set.");
    }
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
  public function getRound(): int {
    return $this->round;
  }
  
  public function setRound(int $round) {
    $this->round = $round;
  }
  
  /**
   * Adds new entry
   */
  public function log($action, $result, CharacterEntity $character1, CharacterEntity $character2, int $amount = 0, string $name = ""): void {
    $this->actions[$this->round][] = new CombatAction($action, $result, $character1, $character2, $amount, $name);
  }
  
  /**
   * Adds text entry
   */
  public function logText(string $text): void {
    $this->actions[$this->round][] = $text;
  }
  
  public function __toString(): string {
    $params = [
      "team1" => $this->team1, "team2" => $this->team2, "actions" => $this->actions
    ];
    $this->latte->setTempDirectory(__DIR__ . "/../../temp/combats");
    return $this->latte->renderToString(__DIR__ . "/../templates/CombatLog.latte", $params);
  }
  
  public function count(): int {
    return count($this->actions);
  }
  
  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->actions);
  }
}
?>