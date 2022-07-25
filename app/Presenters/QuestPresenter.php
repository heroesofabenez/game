<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
final class QuestPresenter extends BasePresenter {
  private \HeroesofAbenez\Model\Quest $model;
  
  public function __construct(\HeroesofAbenez\Model\Quest $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  /**
   * Page /quest does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException();
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderView(int $id): void {
    $quest = $this->model->view($id);
    if($quest === null) {
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
?>