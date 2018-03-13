<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

/**
 * Basic Ranking Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
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
  /** @var \Nette\Utils\Paginator|NULL */
  protected $paginator = NULL;
  
  public function __construct(string $name, array $cols, string $presenter, string $lastCol) {
    parent::__construct();
    $this->name = $name;
    $this->cols = $cols;
    $this->presenter = $presenter;
    $this->lastCol = $lastCol;
  }
  
  public function setPaginator(\Nette\Utils\Paginator $paginator) {
    $this->paginator = $paginator;
  }
  
  abstract public function getData(): array;
  
  public function render(): void {
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