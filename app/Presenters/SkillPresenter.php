<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Skill
 *
 * @author Jakub Konečný
 */
class SkillPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Skills @autowire */
  protected $model;
  
  protected function startup(): void {
    parent::startup();
    if($this->action != "default") {
      $this->template->level = $this->user->identity->level;
      $this->template->class = $this->user->identity->occupation;
    }
  }
  
  /**
   * Page /skill does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException;
  }
  
  public function renderAttack(int $id): void {
    $skill = $this->model->getAttackSkill($id);
    if(!$skill) {
      $this->forward("notfound");
    }
    $this->template->skill = $skill;
  }
  
  public function renderSpecial(int $id): void {
    $skill = $this->model->getSpecialSkill($id);
    if(!$skill) {
      $this->forward("notfound");
    }
    $this->template->skill = $skill;
  }
}
?>