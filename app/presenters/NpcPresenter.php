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
  /** @var \HeroesofAbenez\NPC */
  protected $npc;
  
  /**
   * @param \HeroesofAbenez\NPCModel $npcModel
   */
  function __construct(\HeroesofAbenez\NPCModel $npcModel) {
    $this->model = $npcModel;
  }
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    if($this->action != "default") {
      $this->npc = $this->model->view($this->params["id"]);
      if(!$this->npc) $this->forward("notfound");
      if($this->npc->stage !== $this->user->identity->stage) $this->forward("unavailable");
    }
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
    $this->template->id = $id;
    $this->template->name = $this->npc->name;
    $this->template->description = $this->npc->description;
    $this->template->type = $this->npc->type;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderTalk($id) {
    $this->template->npcId = $id;
    $this->template->npcName = $this->npc->name;
    $this->template->playerName = $playerName = $this->user->identity->name;
    $names = array($this->npc->name, $playerName);
    $this->template->texts = new HOA\Dialogue($names);
    $this->template->texts->addLine("npc", "Greetings, #playerName#. Can I help you with anything?");
    $this->template->texts->addLine("player", "Hail, #npcName#. Not now but thank you.");
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderQuests($id) {
    $this->template->id = $id;
    $this->template->name = $this->npc->name;
    $questModel = $this->context->getService("model.quest");
    $this->template->quests = $questModel->availableQuests($id);
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade($id) {
    if($this->npc->type != "shop") {
      $this->flashMessage("This npc doesn't have shop.");
      $this->redirect("view", $id);
    }
    $this->template->npcName = $this->npc->name;
    $this->model->itemModel = $this->context->getService("model.item");
    $this->template->items = $this->model->shop($id);
  }
}
?>