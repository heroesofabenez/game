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
  protected $cols = array();
  /** @var string */
  protected $lastCol;
  /** @var string */
  protected $presenter;
  /** @var array */
  protected $rankings;
  
  function __construct($name, array $cols, $presenter, $lastCol) {
    $this->name = $name;
    $this->cols = $cols;
    $this->presenter = $presenter;
    $this->lastCol = $lastCol;
    $this->rankings = array("characters", "guilds");
  }
  
  abstract function getData();
  
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/ranking.latte");
    $template->name = $this->name;
    $template->rankings = $this->rankings;
    $template->cols = $this->cols;
    $template->presenter = $this->presenter;
    $template->lastCol = $this->lastCol;
    $template->rows = $this->getData();
    $template->render();
  }
}
?>