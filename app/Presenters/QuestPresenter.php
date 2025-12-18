<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Quest;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
final class QuestPresenter extends BasePresenter
{
    public function __construct(private readonly Quest $model)
    {
        parent::__construct();
    }

    /**
     * Page /quest does not exist
     *
     * @throws \Nette\Application\BadRequestException
     */
    public function actionDefault(): never
    {
        throw new \Nette\Application\BadRequestException();
    }

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function renderView(int $id): void
    {
        $quest = $this->model->view($id);
        if ($quest === null) {
            throw new \Nette\Application\BadRequestException();
        }
        $this->template->quest = $quest;
        $this->template->finished = $this->model->isFinished($id);
        $this->template->requirements = $this->model->getRequirements($quest);
        $this->template->level = $this->user->identity->level;
        $this->template->class = $this->user->identity->class;
        $this->template->race = $this->user->identity->race;
    }
}
