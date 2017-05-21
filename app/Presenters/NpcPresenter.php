<?php
declare(strict_types=1);

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
  /** @var \HeroesofAbenez\Orm\NpcDummy */
  protected $npc;
  
  /**
   * @return void
   */
  function startup(): void {
    parent::startup();
    if($this->action != "default" AND $this->action != "notfound") {
      $this->npc = $this->model->view((int) $this->params["id"]);
      if(is_null($this->npc)) {
        $this->forward("notfound");
      }
      if($this->npc->stage !== $this->user->identity->stage) {
        $this->forward("unavailable");
      }
    }
  }
  
  /**
   * Page /quest does not exist
   * 
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function actionDefault(): void {
    throw new \Nette\Application\BadRequestException;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderView(int $id): void {
    $this->template->id = $id;
    $this->template->type = $this->npc->type;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTalk(int $id): void {
    
  }
  
  /**
   * @return NPC\NPCDialogueControl
   */
  protected function createComponentNpcDialogue(NPC\INPCDialogueControlFactory $factory): NPC\NPCDialogueControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function renderQuests(int $id): void {
    $this->template->id = $id;
  }
  
  /**
   * @return NPC\NPCQuestsControl
   */
  protected function createComponentNpcQuests(NPC\INPCQuestsControlFactory $factory): NPC\NPCQuestsControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade(int $id): void {
    if($this->npc->type != "shop") {
      $this->flashMessage($this->translator->translate("errors.npc.noShop"));
      $this->redirect("view", $id);
    }
  }
  
  /**
   * @param NPC\INPCShopControlFactory $factory
   * @return NPC\NPCQuestsControl
   */
  protected function createComponentNpcShop(NPC\INPCShopControlFactory $factory): NPC\NPCQuestsControl {
    $shop = $factory->create();
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>