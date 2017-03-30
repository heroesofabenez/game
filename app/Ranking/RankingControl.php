<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

/**
 * Basic Ranking Control
 *
 * @author Jakub Konečný
 * @property-write \Nette\Utils\Paginator $paginator
 */
abstract class RankingControl extends \Nette\Application\UI\Control {
  /** @var string */
  protected $name;
  /** @var array */
  protected $cols = [];
  /** @var string */
  protected $lastCol;
  /** @var string */
  protected $presenter;
  /** @var \Nette\Utils\Paginator */
  protected $paginator = false;
  
  /**
   * 
   * @param string $name
   * @param array $cols
   * @param string $presenter
   * @param string $lastCol
   */
  function __construct($name, array $cols, $presenter, $lastCol) {
    $this->name = $name;
    $this->cols = $cols;
    $this->presenter = $presenter;
    $this->lastCol = $lastCol;
  }
  
  function setPaginator(\Nette\Utils\Paginator $paginator) {
    $this->paginator = $paginator;
  }
  
  abstract function getData();
  
  /**
   * @return void
   */
  function render(): void {
    $this->template->setFile(__DIR__ . "/ranking.latte");
    $this->template->name = $this->name;
    $this->template->rankings = ["characters", "guilds"];
    $this->template->cols = $this->cols;
    $this->template->presenter = $this->presenter;
    $this->template->lastCol = $this->lastCol;
    $this->template->rows = $this->getData();
    $this->template->paginator = $this->paginator;
    $this->template->render();
  }
}
?>