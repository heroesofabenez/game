<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Nette\Bridges\ApplicationLatte\ILatteFactory,
    Nette\Localization\ITranslator;

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
  /** @var ITranslator */
  protected $translator;
  /** @var Team First team */
  protected $team1;
  /** @var Team Second team */
  protected $team2;
  /** @var array */
  protected $actions = [];
  /** @var int */
  protected $round;
  
  public function __construct(ILatteFactory $latteFactory, ITranslator $translator) {
    $this->latte = $latteFactory->create();
    $this->translator = $translator;
  }
  
  /**
   * Set teams
   */
  public function setTeams(Team $team1, Team $team2): void {
    if(isset($this->team1)) {
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
  public function log(array $action): void {
    $this->actions[$this->round][] = new CombatAction($this->translator, $action);
  }
  
  /**
   * Adds text entry
   */
  public function logText(string $text, array $params = []): void {
    $this->actions[$this->round][] = $this->translator->translate($text, 0, $params);
  }
  
  public function __toString(): string {
    $params = [
      "team1" => $this->team1, "team2" => $this->team2, "actions" => $this->actions
    ];
    $this->latte->setTempDirectory(__DIR__ . "/../../temp/combats");
    return $this->latte->renderToString(__DIR__ . "/CombatLog.latte", $params);
  }
  
  public function count(): int {
    return count($this->actions);
  }
  
  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->actions);
  }
}
?>