<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
class QuestPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\QuestModel */
  protected $model;
  /** @var \HeroesofAbenez\ItemModel */
  protected $itemModel;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.quest");
    $this->itemModel = $this->context->getService("model.item");
  }
  
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
  function renderView($id) {
    $quest = $this->model->view($id);
    if(!$quest) $this->forward("notfound");
    $this->template->id = $quest->id;
    $this->template->name = $quest->name;
    $this->template->introduction = $quest->introduction;
    $npcMOdel = $this->context->getService("model.npc");
    $this->template->npcStart = $npcMOdel->getNpcName($quest->npc_start);
    $this->template->npcEnd = $npcMOdel->getNpcName($quest->npc_end);
    $requirements = array();
    if($quest->cost_money > 0) $requirements[] = "pay {$quest->cost_money} silver marks";
    if($quest->needed_item > 0) {
      $itemName = $this->itemModel->getItemName($quest->needed_item);
      $itemLink = $this->link("Item:view", $quest->needed_item);
      $requirements[] = "get {$quest->item_amount}x <a href=\"$itemLink\">$itemName</a>";
    }
    $npcLink = $this->link("Npc:view", $quest->npc_end);
    if($quest->npc_start != $quest->npc_end) $requirements[] = "talk to <a href=\"$npcLink\">{$this->template->npcEnd}</a>";
    if($quest->npc_start == $quest->npc_end) $requirements[] = "report back to <a href=\"$npcLink\">{$this->template->npcEnd}</a>";
    $this->template->requirements = $requirements;
    $this->template->rewardMoney = $quest->reward_money;
    $this->template->rewardXp = $quest->reward_xp;
  }
  
  /**
   * @param int $id Quest's id
   * @return void
   */
  function actionAccept($id) {
    $quest = $this->model->view($id);
    if(!$quest) $this->forward("notfound");
    $url = $this->link("Npc:quests", $quest->npc_start);
    $this->model->setRequest($this->context->getService("http.request"));
    $result = $this->model->accept($id, $url);
    switch($result) {
case 1:
  $this->flashMessage("Quest accepted.");
  $this->redirect("Npc:quests", $quest->npc_start);
  break;
case 2:
  $this->forward("notfound");
  break;
case 3:
  $this->flashMessage("You are already working on this quest.");
  $this->redirect("Npc:quests", $quest->npc_start);
  break;
case 4:
  $this->flashMessage("You can't accept the quest from this location.");
  $this->redirect("Homepage:default");
  break;
case 5:
  $this->flashMessage("An error occured.");
  $this->redirect("Npc:quests", $quest->npc_start);
  break;
    }
  }
  
  /**
   * @param int $id Quest's id
   * @return void
   */
  function actionFinish($id) { }
}
?>