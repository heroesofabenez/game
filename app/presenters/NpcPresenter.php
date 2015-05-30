<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
class NpcPresenter extends BasePresenter {
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderDefault($id) {
    $npc = HOA\NPCModel::view($id, $this->context);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
    $this->template->id = $id;
    $this->template->name = $npc->name;
    $this->template->description = $npc->description;
    $this->template->type = $npc->type;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTalk($id) {
    $npc = HOA\NPCModel::view($id, $this->context);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionQuests($id) {
    $npc = HOA\NPCModel::view($id, $this->context);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade($id) {
    $npc = HOA\NPCModel::view($id, $this->context);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
  }
}