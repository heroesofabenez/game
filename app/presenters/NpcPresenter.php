<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez as HOA;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
class NpcPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\NPCModel */
  protected $model;
  
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.npc");
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
   * @param int $id Npc's id
   * @return void
   */
  function renderView($id) {
    $npc = $this->model->view($id);
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
  function renderTalk($id) {
    $npc = $this->model->view($id);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
    $this->template->npcId = $id;
    $this->template->npcName = $npc->name;
    $this->template->playerName = $playerName = $this->user->identity->name;
    $names = array($npc->name, $playerName);
    $this->template->texts = array(
      new HOA\DialogueLine("npc", "Greetings, #playerName#. Can I help you with anything?", $names),
      new HOA\DialogueLine("player", "Hail, #npcName#. Not now but thank you.", $names)
    );
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderQuests($id) {
    $npc = $this->model->view($id);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
    $this->template->id = $id;
    $this->template->name = $npc->name;
    $questModel = $this->context->getService("model.quest");
    $this->template->quests = $questModel->availableQuests($id);
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade($id) {
    $npc = $this->model->view($id);
    if(!$npc) $this->forward("notfound");
    if($npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
  }
}