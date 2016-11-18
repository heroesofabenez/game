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
  
  /**
   * @return void
   */
  protected function startup() {
    parent::startup();
    if($this->action != "default") {
      $this->template->level = $this->user->identity->level;
      $this->template->class = $this->user->identity->occupation;
    }
  }
  
  /**
   * Page /skill does not exist
   * 
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function actionDefault() {
    throw new \Nette\Application\BadRequestException;
  }
  
  /**
   * @param int $id
   * @return void
   */
  function renderAttack(int $id) {
    $skill = $this->model->getAttackSkill($id);
    if(!$skill) {
      $this->forward("notfound");
    }
    $this->template->skill = $skill;
  }
  
  /**
   * @param int $id
   * @return void
   */
  function renderSpecial(int $id) {
    $skill = $this->model->getSpecialSkill($id);
    if(!$skill) $this->forward("notfound");
    $this->template->skill = $skill;
  }
}
?>