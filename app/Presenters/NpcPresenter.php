<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\NPC\INPCDialogueControlFactory,
    HeroesofAbenez\NPC\NPCDialogueControl,
    HeroesofAbenez\NPC\INPCQuestsControlFactory,
    HeroesofAbenez\NPC\NPCQuestsControl,
    HeroesofAbenez\NPC\INPCShopControlFactory,
    HeroesofAbenez\NPC\NPCShopControl,
    HeroesofAbenez\Orm\Npc;

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
   * @param INPCDialogueControlFactory $factory
   * @return NPCDialogueControl
   */
  protected function createComponentNpcDialogue(INPCDialogueControlFactory $factory): NPCDialogueControl {
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
   * @param INPCQuestsControlFactory $factory
   * @return NPCQuestsControl
   */
  protected function createComponentNpcQuests(INPCQuestsControlFactory $factory): NPCQuestsControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  /**
   * @param int $id Npc's id
   * @return void
   */
  function actionTrade(int $id): void {
    if($this->npc->type != Npc::TYPE_SHOP) {
      $this->flashMessage($this->translator->translate("errors.npc.noShop"));
      $this->redirect("view", $id);
    }
  }
  
  /**
   * @param INPCShopControlFactory $factory
   * @return NPCShopControl
   */
  protected function createComponentNpcShop(INPCShopControlFactory $factory): NPCShopControl {
    $shop = $factory->create();
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>