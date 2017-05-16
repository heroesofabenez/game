<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\InvalidStatException,
    HeroesofAbenez\Model\NoStatPointsAvailableException,
    HeroesofAbenez\Model\InvalidSkillTypeException,
    HeroesofAbenez\Model\NoSkillPointsAvailableException,
    HeroesofAbenez\Model\SkillNotFoundException,
    HeroesofAbenez\Model\SkillMaxLevelReachedException,
    HeroesofAbenez\Model\CannotLearnSkillException;

/**
 * Presenter Training
 *
 * @author Jakub Konečný
 */
class TrainingPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Profile */
  protected $model;
  /** @var \HeroesofAbenez\Model\Skills @autowire */
  protected $skillsModel;
  
  /**
   * @param \HeroesofAbenez\Model\Profile $model
   * @param \Nette\Security\User $user
   */
  function __construct(\HeroesofAbenez\Model\Profile $model, \Nette\Security\User $user) {
    parent::__construct();
    $this->model = $model;
    $this->model->user = $user;
  }
  
  /**
   * @return void
   */
  function renderDefault(): void {
    $this->template->stat_points = $this->model->getStatPoints();
    $this->template->stats = $this->model->getStats();
    $this->template->skill_points = $this->skillsModel->getSkillPoints();
    $this->template->skills = $this->skillsModel->getAvailableSkills();
  }
  
  /**
   * @param string $stat
   * @return void
   */
  function handleTrainStat(string $stat): void {
    try {
      $this->model->trainStat($stat);
    } catch(NoStatPointsAvailableException $e) {
      $this->flashMessage($this->translator->translate("errors.training.noStatPointsAvailable"));
    } catch(InvalidStatException $e) {
      
    }
    $this->redirect("Training:");
  }
  
  /**
   * @param int $skillId
   * @param string $skillType
   * @return void
   */
  function handleTrainSkill(int $skillId, string $skillType): void {
    try {
      $this->skillsModel->trainSkill($skillId, $skillType);
    } catch(NoSkillPointsAvailableException $e) {
      $this->flashMessage($this->translator->translate("errors.training.noSkillPointsAvailable"));
    } catch(SkillMaxLevelReachedException $e) {
      $this->flashMessage($this->translator->translate("errors.training.skillMaxLevelReached"));
    } catch(CannotLearnSkillException $e) {
      $this->flashMessage($this->translator->translate("errors.training.cannotLearnSkill"));
    } catch(InvalidSkillTypeException $e) {
      
    } catch(SkillNotFoundException $e) {
      
    }
    $this->redirect("Training:");
  }
}
?>