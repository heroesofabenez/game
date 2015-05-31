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
  
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.quest");
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
    if($quest->needed_item > 0) $requirements[] = "get {$quest->item_amount}x {$quest->needed_item}";
    if($quest->npc_start != $quest->npc_end) $requirements[] = "talk to {$this->template->npcEnd}";
    if($quest->npc_start == $quest->npc_end) $requirements[] = "report back to {$this->template->npcEnd}";
    $this->template->requirements = $requirements;
    $this->template->rewardMoney = $quest->reward_money;
    $this->template->rewardXp = $quest->reward_xp;
  }
}
?>