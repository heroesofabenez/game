<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
class QuestPresenter extends BasePresenter {
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
    throw new \Nette\Application\BadRequestException;
  }
  
  public function renderView(int $id): void {
    $quest = $this->model->view($id);
    if(is_null($quest)) {
      $this->forward("notfound");
    }
    $this->template->id = $quest->id;
    $this->template->name = $quest->name;
    $this->template->introduction = $quest->introduction;
    $this->template->end_text = $quest->endText;
    $this->template->finished = $this->model->isFinished($id);
    $this->template->npcStart = $this->npcModel->getNpcName($quest->npcStart);
    $this->template->npcEnd = $this->npcModel->getNpcName($quest->npcEnd);
    $requirements = [];
    if($quest->costMoney > 0) {
      $requirements[] = (object) [
        "text" => "pay {$quest->costMoney} silver marks", "met" => false
      ];
    }
    if(is_int($quest->neededItem)) {
      $itemName = $this->itemModel->getItemName($quest->neededItem);
      $itemLink = $this->link("Item:view", $quest->neededItem);
      $haveItem = $this->itemModel->haveItem($quest->neededItem, $quest->itemAmount);
      $requirements[] = (object) [
        "text" => "get {$quest->itemAmount}x <a href=\"$itemLink\">$itemName</a>", "met" => $haveItem
      ];
    }
    $npcLink = $this->link("Npc:view", $quest->npcEnd);
    if($quest->npcStart != $quest->npcEnd) {
      $requirements[] = (object) [
        "text" => "talk to <a href=\"$npcLink\">{$this->template->npcEnd}</a>", "met" => false
      ];
    } else {
      $requirements[] = (object) [
        "text" => "report back to <a href=\"$npcLink\">{$this->template->npcEnd}</a>", "met" => false
      ];
    }
    $this->template->requirements = $requirements;
    $this->template->rewardMoney = $quest->rewardMoney;
    $this->template->rewardXp = $quest->rewardXp;
    $this->template->rewardItem = (is_int($quest->rewardItem)) ? $quest->rewardItem : false;
  }
}
?>