<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Skill
 *
 * @author Jakub Konečný
 */
final class SkillPresenter extends BasePresenter {
  private \HeroesofAbenez\Model\Skills $model;
  
  public function __construct(\HeroesofAbenez\Model\Skills $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  protected function startup(): void {
    parent::startup();
    if($this->action != "default") {
      $this->template->level = $this->user->identity->level;
      $this->template->class = $this->user->identity->class;
      $this->template->specialization = $this->user->identity->specialization;
    }
  }
  
  /**
   * Page /skill does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException();
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderAttack(int $id): void {
    $skill = $this->model->getAttackSkill($id);
    if($skill === null) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->skill = $skill;
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  public function renderSpecial(int $id): void {
    $skill = $this->model->getSpecialSkill($id);
    if($skill === null) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->skill = $skill;
  }
}
?>