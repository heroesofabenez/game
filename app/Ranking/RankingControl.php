<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

/**
 * Basic Ranking Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class RankingControl extends \Nette\Application\UI\Control {
  protected string $name;
  protected array $cols = [];
  protected string $lastCol;
  protected string $presenterName;
  public ?\Nette\Utils\Paginator $paginator = null;
  
  public function __construct(string $name, array $cols, string $presenter, string $lastCol) {
    $this->name = $name;
    $this->cols = $cols;
    $this->presenterName = $presenter;
    $this->lastCol = $lastCol;
  }
  
  abstract public function getData(): array;
  
  public function render(): void {
    $this->template->setFile(__DIR__ . "/ranking.latte");
    $this->template->name = $this->name;
    $this->template->rankings = ["characters", "guilds"];
    $this->template->cols = $this->cols;
    $this->template->presenter = $this->presenterName;
    $this->template->lastCol = $this->lastCol;
    $this->template->rows = $this->getData();
    $this->template->paginator = $this->paginator;
    $this->template->render();
  }
}
?>