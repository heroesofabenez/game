<?php
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
    $this->model = $model;
    $this->model->user = $user;
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $this->template->stat_points = $this->model->getStatPoints();
    $this->template->stats = $this->model->getStats();
    $this->template->skill_points = $this->skillsModel->getSkillPoints();
    $this->template->skills = $this->skillsModel->getAvailableSkills();
  }
  
  /**
   * @param string $stat
   * @return void
   */
  function handleTrainStat($stat) {
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
   */
  function handleTrainSkill($skillId, $skillType) {
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