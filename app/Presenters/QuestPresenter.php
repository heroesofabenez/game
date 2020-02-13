<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
final class QuestPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Quest $model;
  protected \HeroesofAbenez\Model\Item $itemModel;
  protected \HeroesofAbenez\Model\NPC $npcModel;
  
  public function __construct(\HeroesofAbenez\Model\Quest $model, \HeroesofAbenez\Model\Item $itemModel, \HeroesofAbenez\Model\NPC $npcModel) {
    parent::__construct();
    $this->model = $model;
    $this->itemModel = $itemModel;
    $this->npcModel = $npcModel;
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
    $this->template->id = $quest->id;
    $this->template->finished = $this->model->isFinished($id);
    $this->template->npcStart = $quest->npcStart->id;
    $this->template->npcEnd = $this->translator->translate("npcs.{$quest->npcEnd->id}.name");
    $this->template->requirements = $this->model->getRequirements($quest);
    $this->template->rewardMoney = $quest->rewardMoney;
    $this->template->rewardXp = $quest->rewardXp;
    $this->template->rewardItem = ($quest->rewardItem !== null) ? $quest->rewardItem->id : false;
    $this->template->rewardWhiteKarma = $quest->rewardWhiteKarma;
    $this->template->rewardDarkKarma = $quest->rewardDarkKarma;
    $this->template->rewardPet = ($quest->rewardPet !== null) ? $quest->rewardPet->id : null;
    $this->template->followupQuests = $quest->children;
    $this->template->requiredQuest = $quest->requiredQuest;
    $this->template->level = $this->user->identity->level;
    $this->template->requiredLevel = $quest->requiredLevel;
    $this->template->class = $this->user->identity->class;
    $this->template->requiredClass = ($quest->requiredClass !== null) ? $quest->requiredClass->id : null;
    $this->template->race = $this->user->identity->race;
    $this->template->requiredRace = ($quest->requiredRace !== null) ? $quest->requiredRace->id : null;
  }
}
?>