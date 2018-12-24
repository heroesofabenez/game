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
use HeroesofAbenez\Model\ItemNotFoundException;
use HeroesofAbenez\Model\ItemNotOwnedException;
use HeroesofAbenez\Model\InsufficientFundsException;
use HeroesofAbenez\Model\ItemNotDamagedException;

/**
 * Presenter Npc
 *
 * @author Jakub Konečný
 */
final class NpcPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\NPC */
  protected $model;
  /** @var \HeroesofAbenez\Model\Journal */
  protected $journalModel;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var Npc */
  protected $npc;

  public function __construct(\HeroesofAbenez\Model\NPC $model, \HeroesofAbenez\Model\Journal $journalModel, \HeroesofAbenez\Model\Item $itemModel) {
    parent::__construct();
    $this->model = $model;
    $this->journalModel = $journalModel;
    $this->itemModel = $itemModel;
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
    $this->template->smith = $this->npc->smith;
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

  public function renderRepair(int $id): void {
    if(!$this->npc->smith) {
      $this->flashMessage("errors.npc.notSmith");
      $this->redirect("view", $id);
    }
    $this->template->items = $this->journalModel->inventory()["items"];
  }

  public function handleRepair(int $itemId): void {
    try {
      $this->itemModel->repairItem($itemId);
      $this->flashMessage("messages.equipment.repaired");
    } catch(ItemNotFoundException | ItemNotOwnedException $e) {
      $this->redirect("Item:notfound");
    } catch(ItemNotDamagedException $e) {
      $this->flashMessage("errors.equipment.notDamaged");
    } catch(InsufficientFundsException $e) {
      $this->flashMessage("errors.equipment.notEnoughMoney");
    }
    $this->redirect("this");
  }
}
?>