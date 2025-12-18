<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\CombatHelper;
use HeroesofAbenez\Model\InvalidStatException;
use HeroesofAbenez\Model\NoStatPointsAvailableException;
use HeroesofAbenez\Model\InvalidSkillTypeException;
use HeroesofAbenez\Model\NoSkillPointsAvailableException;
use HeroesofAbenez\Model\Profile;
use HeroesofAbenez\Model\SkillNotFoundException;
use HeroesofAbenez\Model\SkillMaxLevelReachedException;
use HeroesofAbenez\Model\CannotLearnSkillException;
use HeroesofAbenez\Model\Skills;

/**
 * Presenter Training
 *
 * @author Jakub Konečný
 */
final class TrainingPresenter extends BasePresenter
{
    public function __construct(private readonly Profile $model, private readonly Skills $skillsModel, private readonly \Nette\Security\User $user, private readonly CombatHelper $combatHelper)
    {
        parent::__construct();
    }

    public function renderDefault(): void
    {
        $this->model->user = $this->user;
        $this->template->statPoints = $this->model->getStatPoints();
        $this->template->skillPoints = $this->skillsModel->getSkillPoints();
        $this->template->stats = $this->model->getStats();
        $this->template->skills = $this->skillsModel->getAvailableSkills();
        $this->template->charismaBonus = $this->model->getCharismaBonus();
        $character = $this->combatHelper->getPlayer($this->user->id);
        $character->applyEffectProviders();
        $this->template->character = $character;
        $this->template->damageStat = $character->damageStat();
    }

    public function handleTrainStat(string $stat): void
    {
        try {
            $this->model->user = $this->user;
            $this->model->trainStat($stat);
        } catch (NoStatPointsAvailableException) {
            $this->flashMessage("errors.training.noStatPointsAvailable");
        } catch (InvalidStatException) {
        }
        $this->redirect("Training:");
    }

    public function handleTrainSkill(int $skillId, string $skillType): void
    {
        try {
            $this->model->user = $this->user;
            $this->skillsModel->trainSkill($skillId, $skillType);
        } catch (NoSkillPointsAvailableException) {
            $this->flashMessage("errors.training.noSkillPointsAvailable");
        } catch (SkillMaxLevelReachedException) {
            $this->flashMessage("errors.training.skillMaxLevelReached");
        } catch (CannotLearnSkillException) {
            $this->flashMessage("errors.training.cannotLearnSkill");
        } catch (InvalidSkillTypeException) {
        } catch (SkillNotFoundException) {
        }
        $this->redirect("Training:");
    }
}
