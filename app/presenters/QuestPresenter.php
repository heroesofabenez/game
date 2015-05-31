<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Quest
 *
 * @author Jakub Konečný
 */
class QuestPresenter extends BasePresenter {
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
    $quest = HOA\QuestModel::view($id, $this->context);
    if(!$quest) $this->forward("notfound");
    $this->template->id = $quest->id;
    $this->template->name = $quest->name;
    $this->template->introduction = $quest->introduction;
    $this->template->npcStart = HOA\NPCModel::getNpcName($quest->npc_start, $this->context);
    $this->template->npcEnd = HOA\NPCModel::getNpcName($quest->npc_end, $this->context);
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