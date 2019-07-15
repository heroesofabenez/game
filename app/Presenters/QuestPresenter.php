<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
final class QuestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $model;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
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
    if(is_null($quest)) {
      throw new \Nette\Application\BadRequestException();
    }
    $this->template->id = $quest->id;
    $this->template->finished = $this->model->isFinished($id);
    $this->template->npcStart = $quest->npcStart->id;
    $this->template->npcEnd = $this->translator->translate("npcs.{$quest->npcEnd->id}.name");
    $this->template->requirements = $this->model->getRequirements($quest);
    $this->template->rewardMoney = $quest->rewardMoney;
    $this->template->rewardXp = $quest->rewardXp;
    $this->template->rewardItem = (!is_null($quest->rewardItem)) ? $quest->rewardItem->id : false;
    $this->template->rewardWhiteKarma = $quest->rewardWhiteKarma;
    $this->template->rewardDarkKarma = $quest->rewardDarkKarma;
    $this->template->rewardPet = (!is_null($quest->rewardPet)) ? $quest->rewardPet->id : null;
    $this->template->followupQuests = $quest->children;
    $this->template->requiredQuest = $quest->requiredQuest;
    $this->template->level = $this->user->identity->level;
    $this->template->requiredLevel = $quest->requiredLevel;
    $this->template->class = $this->user->identity->class;
    $this->template->requiredClass = (!is_null($quest->requiredClass)) ? $quest->requiredClass->id : null;
    $this->template->race = $this->user->identity->race;
    $this->template->requiredRace = (!is_null($quest->requiredRace)) ? $quest->requiredRace->id : null;
  }
}
?>