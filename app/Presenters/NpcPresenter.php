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
  /** @var Npc */
  protected $npc;
  
  public function startup(): void {
    parent::startup();
    if($this->action != "default" AND !in_array($this->action, ["notfound", "unavailable"])) {
      $this->npc = $this->model->view((int) $this->params["id"]);
      if(is_null($this->npc)) {
        $this->forward("notfound");
      }
      if($this->npc->stage->id !== $this->user->identity->stage) {
        $this->forward("unavailable");
      }
    }
  }
  
  /**
   * Page /quest does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): void {
    throw new \Nette\Application\BadRequestException;
  }
  
  public function renderView(int $id): void {
    $this->template->id = $id;
    $this->template->type = $this->npc->type;
  }
  
  public function actionTalk(int $id): void {
    
  }
  
  protected function createComponentNpcDialogue(INPCDialogueControlFactory $factory): NPCDialogueControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  public function renderQuests(int $id): void {
    $this->template->id = $id;
  }
  
  protected function createComponentNpcQuests(INPCQuestsControlFactory $factory): NPCQuestsControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  public function actionTrade(int $id): void {
    if($this->npc->type != Npc::TYPE_SHOP) {
      $this->flashMessage($this->translator->translate("errors.npc.noShop"));
      $this->redirect("view", $id);
    }
  }
  
  protected function createComponentNpcShop(INPCShopControlFactory $factory): NPCShopControl {
    $shop = $factory->create();
    $shop->npc = $this->npc;
    return $shop;
  }
}
?>