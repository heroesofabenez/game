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
  /** @var \HeroesofAbenez\Model\CombatHelper */
  protected $combatHelper;
  
  public function __construct(\HeroesofAbenez\Model\Profile $model, \HeroesofAbenez\Model\Skills $skillsModel, \Nette\Security\User $user, \HeroesofAbenez\Model\CombatHelper $combatHelper) {
    parent::__construct();
    $this->model = $model;
    $this->model->user = $user;
    $this->skillsModel = $skillsModel;
    $this->combatHelper = $combatHelper;
  }
  
  public function renderDefault(): void {
    $this->template->statPoints = $this->model->getStatPoints();
    $this->template->skillPoints = $this->skillsModel->getSkillPoints();
    $this->template->stats = $this->model->getStats();
    $this->template->skills = $this->skillsModel->getAvailableSkills();
    $character = $this->combatHelper->getPlayer($this->user->id);
    $character->applyEffectProviders();
    $this->template->character = $character;
    $this->template->damageStat = $character->damageStat();
  }
  
  public function handleTrainStat(string $stat): void {
    try {
      $this->model->trainStat($stat);
    } catch(NoStatPointsAvailableException $e) {
      $this->flashMessage("errors.training.noStatPointsAvailable");
    } catch(InvalidStatException $e) {
      
    }
    $this->redirect("Training:");
  }
  
  public function handleTrainSkill(int $skillId, string $skillType): void {
    try {
      $this->skillsModel->trainSkill($skillId, $skillType);
    } catch(NoSkillPointsAvailableException $e) {
      $this->flashMessage("errors.training.noSkillPointsAvailable");
    } catch(SkillMaxLevelReachedException $e) {
      $this->flashMessage("errors.training.skillMaxLevelReached");
    } catch(CannotLearnSkillException $e) {
      $this->flashMessage("errors.training.cannotLearnSkill");
    } catch(InvalidSkillTypeException $e) {
      
    } catch(SkillNotFoundException $e) {
      
    }
    $this->redirect("Training:");
  }
}
?>