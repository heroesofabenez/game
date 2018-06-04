<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\InvalidStatException;
use HeroesofAbenez\Model\NoStatPointsAvailableException;
use HeroesofAbenez\Model\InvalidSkillTypeException;
use HeroesofAbenez\Model\NoSkillPointsAvailableException;
use HeroesofAbenez\Model\SkillNotFoundException;
use HeroesofAbenez\Model\SkillMaxLevelReachedException;
use HeroesofAbenez\Model\CannotLearnSkillException;

/**
 * Presenter Training
 *
 * @author Jakub Konečný
 */
final class TrainingPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Profile */
  protected $model;
  /** @var \HeroesofAbenez\Model\Skills */
  protected $skillsModel;
  
  public function __construct(\HeroesofAbenez\Model\Profile $model, \HeroesofAbenez\Model\Skills $skillsModel, \Nette\Security\User $user) {
    parent::__construct();
    $this->model = $model;
    $this->model->user = $user;
    $this->skillsModel = $skillsModel;
  }
  
  public function renderDefault(): void {
    $this->template->stat_points = $this->model->getStatPoints();
    $this->template->stats = $this->model->getStats();
    $this->template->skill_points = $this->skillsModel->getSkillPoints();
    $this->template->skills = $this->skillsModel->getAvailableSkills();
  }
  
  public function handleTrainStat(string $stat): void {
    try {
      $this->model->trainStat($stat);
    } catch(NoStatPointsAvailableException $e) {
      $this->flashMessage($this->translator->translate("errors.training.noStatPointsAvailable"));
    } catch(InvalidStatException $e) {
      
    }
    $this->redirect("Training:");
  }
  
  public function handleTrainSkill(int $skillId, string $skillType): void {
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