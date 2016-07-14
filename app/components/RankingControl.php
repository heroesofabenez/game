<?php
namespace HeroesofAbenez\Ranking;

/**
 * Basic Ranking Control
 *
 * @author Jakub Konečný
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
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/ranking.latte");
    $template->name = $this->name;
    $template->rankings = ["characters", "guilds"];
    $template->cols = $this->cols;
    $template->presenter = $this->presenter;
    $template->lastCol = $this->lastCol;
    $template->rows = $this->getData();
    $template->paginator = $this->paginator;
    $template->render();
  }
}
?>