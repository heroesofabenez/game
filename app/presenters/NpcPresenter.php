<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\NPC;

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
  protected function createComponentNpcDialogue(NPC\NPCDialogueControlFactory $factory) {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderQuests($id) {
    $this->template->id = $id;
  }
  
  /**
   * @return \HeroesofAbenez\NPC\NPCQuestsControl
   */
  protected function createComponentNpcQuests(NPC\NPCQuestsControlFactory $factory) {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade($id) {
    if($this->npc->type != "shop") {
      $this->flashMessage($this->translator->translate("errors.npc.noShop"));
      $this->redirect("view", $id);
    }
  }
  
  /**
   * @return \HeroesofAbenez\NPC\ShopControl
   */
  protected function createComponentNpcShop(NPC\NPCShopControlFactory $factory) {
    $shop = $factory->create();
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>