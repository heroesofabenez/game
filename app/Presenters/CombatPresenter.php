<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\CombatLogManager;

/**
 * Presenter Combat
 *
 * @author Jakub Konečný
 */
final class CombatPresenter extends BasePresenter
{
    public function __construct(private readonly CombatLogManager $log)
    {
        parent::__construct();
    }

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function actionView(int $id): void
    {
        $combat = $this->log->read($id);
        if ($combat === null) {
            throw new \Nette\Application\BadRequestException();
        }
        $this->template->log = $combat->text;
    }
}
