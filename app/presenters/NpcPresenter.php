<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
class NpcPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\NPC @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
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
  protected function createComponentNpcDialogue() {
    $component = $this->context->getService("npc.dialogue")->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderQuests($id) {
    $this->template->name = $this->npc->name;
  }
  
  /**
   * @return \HeroesofAbenez\NPC\NPCQuestsControl
   */
  protected function createComponentNpcQuests() {
    $component = $this->context->getService("npc.quests")->create();
    $component->npc = $this->npc;
    return $component;
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
  protected function createComponentNpcShop() {
    $shop = $this->context->getService("npc.shop")->create();
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>