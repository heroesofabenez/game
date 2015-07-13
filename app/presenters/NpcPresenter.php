<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
class NpcPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\NPC */
  protected $model;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  /**
   * @param \HeroesofAbenez\Model\NPC $npcModel
   */
  function __construct(\HeroesofAbenez\Model\NPC $npcModel) {
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
  function actionTalk($id) {
    
  }
  
  /**
   * @return \HeroesofAbenez\Model\NPCDialogueControl
   */
  function createComponentNpcDialogue() {
    $component = $this->context->getService("npc_dialogue");
    $component->npc = $this->npc;
    return $component;
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
  }
  
  /**
   * @return \HeroesofAbenez\NPC\ShopControl
   */
  function createComponentNpcShop() {
    $shop = $this->context->getService("npc.shop");
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>