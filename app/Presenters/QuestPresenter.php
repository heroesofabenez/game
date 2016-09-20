<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
class QuestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Quest @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Item @autowire */
  protected $itemModel;
  /** @var \HeroesofAbenez\Model\NPC @autowire */
  protected $npcModel;
  
  /**
   * Page /quest does not exist
   * 
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function actionDefault() {
    throw new \Nette\Application\BadRequestException;
  }
  
  /**
   * @param int $id Quest's id
   * @return void
   */
  function renderView(int $id) {
    $quest = $this->model->view($id);
    if(!$quest) $this->forward("notfound");
    $this->template->id = $quest->id;
    $this->template->name = $quest->name;
    $this->template->introduction = $quest->introduction;
    $this->template->end_text = $quest->end_text;
    $this->template->finished = $this->model->isFinished($id);
    $this->template->npcStart = $this->npcModel->getNpcName($quest->npc_start);
    $this->template->npcEnd = $this->npcModel->getNpcName($quest->npc_end);
    $requirements = [];
    if($quest->cost_money > 0) {
      $requirements[] = (object) [
        "text" => "pay {$quest->cost_money} silver marks", "met" => false
      ];
    }
    if($quest->needed_item > 0) {
      $itemName = $this->itemModel->getItemName($quest->needed_item);
      $itemLink = $this->link("Item:view", $quest->needed_item);
      $haveItem = $this->itemModel->haveItem($quest->needed_item, $quest->item_amount);
      $requirements[] = (object) [
        "text" => "get {$quest->item_amount}x <a href=\"$itemLink\">$itemName</a>", "met" => $haveItem
      ];
    }
    $npcLink = $this->link("Npc:view", $quest->npc_end);
    if($quest->npc_start != $quest->npc_end) {
      $requirements[] = (object) [
        "text" => "talk to <a href=\"$npcLink\">{$this->template->npcEnd}</a>", "met" => false
      ];
    } else {
      $requirements[] = (object) [
        "text" => "report back to <a href=\"$npcLink\">{$this->template->npcEnd}</a>", "met" => false
      ];
    }
    $this->template->requirements = $requirements;
    $this->template->rewardMoney = $quest->reward_money;
    $this->template->rewardXp = $quest->reward_xp;
    $this->template->rewardItem = (is_int($quest->reward_item)) ? $quest->reward_item : false;
  }
}
?>