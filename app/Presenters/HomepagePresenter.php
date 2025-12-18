<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Location;

/**
 * Presenter Homepage
 *
 * @author Jakub Konečný
 */
final class HomepagePresenter extends BasePresenter
{
    public function __construct(private readonly Location $model, \Nette\Security\User $user)
    {
        $this->model->user = $user;
        parent::__construct();
    }

    public function renderDefault(): void
    {
        $stage = $this->model->getStage($this->user->identity->stage);
        $this->template->stage = $stage;
    }
}
