<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\NPC\INPCDialogueControlFactory;
use HeroesofAbenez\NPC\NPCDialogueControl;
use HeroesofAbenez\NPC\INPCQuestsControlFactory;
use HeroesofAbenez\NPC\NPCQuestsControl;
use HeroesofAbenez\NPC\INPCShopControlFactory;
use HeroesofAbenez\NPC\NPCShopControl;
use HeroesofAbenez\Orm\Npc;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
final class NpcPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\NPC */
  protected $model;
  /** @var Npc */
  protected $npc;
  
  public function __construct(\HeroesofAbenez\Model\NPC $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  protected function startup(): void {
    parent::startup();
    if($this->action !== "default" AND !in_array($this->action, ["notfound", "unavailable"], true)) {
      $npc = $this->model->view((int) $this->params["id"]);
      if(is_null($npc)) {
        $this->forward("notfound");
      }
      $this->npc = $npc;
      if($this->npc->stage->id !== $this->user->identity->stage AND $this->action !== "view") {
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
    throw new \Nette\Application\BadRequestException();
  }
  
  public function renderView(int $id): void {
    $this->template->id = $id;
    $this->template->quests = $this->npc->quests;
    $this->template->shop = $this->npc->shop;
    $this->template->fight = $this->npc->fight;
    $this->template->canInteract = ($this->npc->stage->id === $this->user->identity->stage);
    if(!$this->template->canInteract) {
      $this->template->stage = $this->npc->stage->id;
      $this->template->area = $this->npc->stage->area->id;
    }
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
  
  public function actionFight(int $id): void {
    if(!$this->npc->fight) {
      $this->flashMessage("errors.npc.notEnemy");
      $this->redirect("view", $id);
    }
  }
  
  protected function createComponentNpcQuests(INPCQuestsControlFactory $factory): NPCQuestsControl {
    $component = $factory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  public function actionTrade(int $id): void {
    if(!$this->npc->shop) {
      $this->flashMessage("errors.npc.noShop");
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