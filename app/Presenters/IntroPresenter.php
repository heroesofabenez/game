<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Intro;

/**
 * Presenter Intro
 *
 * @author Jakub Konečný
 */
final class IntroPresenter extends BasePresenter
{
    private int $part;

    public function __construct(private readonly Intro $model)
    {
        parent::__construct();
    }

    protected function startup(): void
    {
        $this->reloadIdentity();
        parent::startup();
        $this->part = $this->template->part = (int) $this->model->getIntroPosition();
    }

    public function renderDefault(): void
    {
        $text = $this->model->getIntroPart($this->part);
        if ($text === "") {
            $this->forward("Intro:end");
        }
        $this->template->intro = $text;
    }

    public function actionNext(): never
    {
        $this->model->moveToNextPart();
        $this->redirect("Intro:");
    }

    public function actionEnd(): never
    {
        $this->model->endIntro();
        $this->reloadIdentity();
        $this->redirect("Homepage:");
    }
}
