<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

/**
 * Basic Ranking Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class RankingControl extends \Nette\Application\UI\Control
{
    public ?\Nette\Utils\Paginator $paginator = null;

    public function __construct(protected readonly string $name, protected readonly array $cols, protected readonly string $presenterName, protected readonly string $lastCol)
    {
    }

    abstract public function getData(): array;

    public function render(): void
    {
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
