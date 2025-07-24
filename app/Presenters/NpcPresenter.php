<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Item;
use HeroesofAbenez\Model\Journal;
use HeroesofAbenez\Model\NPC;
use HeroesofAbenez\NPC\INPCDialogueControlFactory;
use HeroesofAbenez\NPC\NPCDialogueControl;
use HeroesofAbenez\NPC\INPCQuestsControlFactory;
use HeroesofAbenez\NPC\NPCQuestsControl;
use HeroesofAbenez\NPC\INPCShopControlFactory;
use HeroesofAbenez\NPC\NPCShopControl;
use HeroesofAbenez\Orm\Npc as NpcEntity;
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
  private NpcEntity $npc;
  private INPCDialogueControlFactory $npcDialogueFactory;
  private INPCQuestsControlFactory $npcQuestsFactory;
  private INPCShopControlFactory $npcShopFactory;

  public function __construct(private readonly NPC $model, private readonly Journal $journalModel, private readonly Item $itemModel) {
    parent::__construct();
  }

  public function injectNpcDialogueFactory(INPCDialogueControlFactory $npcDialogueFactory): void {
    $this->npcDialogueFactory = $npcDialogueFactory;
  }

  public function injectNpcQuestsFactory(INPCQuestsControlFactory $npcQuestsFactory): void {
    $this->npcQuestsFactory = $npcQuestsFactory;
  }

  public function injectNpcShopFactory(INPCShopControlFactory $npcShopFactory): void {
    $this->npcShopFactory = $npcShopFactory;
  }

  /**
   * @throws \Nette\Application\BadRequestException
   */
  protected function startup(): void {
    parent::startup();
    if($this->action !== "default" && !in_array($this->action, ["notfound", "unavailable"], true)) {
      $npc = $this->model->view((int) $this->params["id"]);
      if($npc === null) {
        throw new \Nette\Application\BadRequestException();
      }
      $this->npc = $npc;
      if($this->npc->stage->id !== $this->user->identity->stage && $this->action !== "view") {
        $this->forward("unavailable");
      }
    }
  }
  
  /**
   * Page /quest does not exist
   *
   * @throws \Nette\Application\BadRequestException
   */
  public function actionDefault(): never {
    throw new \Nette\Application\BadRequestException();
  }
  
  public function renderView(int $id): void {
    $this->template->npc = $this->npc;
    $this->template->canInteract = ($this->npc->stage->id === $this->user->identity->stage);
  }
  
  public function actionTalk(int $id): void {
  }
  
  protected function createComponentNpcDialogue(): NPCDialogueControl {
    $component = $this->npcDialogueFactory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  public function renderQuests(int $id): void {
    $this->template->npc = $this->npc;
  }
  
  public function actionFight(int $id): void {
    if(!$this->npc->fight) {
      $this->flashMessage("errors.npc.notEnemy");
      $this->redirect("view", $id);
    }
  }
  
  protected function createComponentNpcQuests(): NPCQuestsControl {
    $component = $this->npcQuestsFactory->create();
    $component->npc = $this->npc;
    return $component;
  }
  
  public function actionTrade(int $id): void {
    if(!$this->npc->shop) {
      $this->flashMessage("errors.npc.noShop");
      $this->redirect("view", $id);
    }
  }
  
  protected function createComponentNpcShop(): NPCShopControl {
    $shop = $this->npcShopFactory->create();
    $shop->npc = $this->npc;
    return $shop;
  }

  public function renderRepair(int $id): void {
    if(!$this->npc->smith) {
      $this->flashMessage("errors.npc.notSmith");
      $this->redirect("view", $id);
    }
    $inventory = $this->journalModel->inventory();
    $this->template->items = $inventory["items"];
    $this->template->money = $inventory["money"];
  }

  public function handleRepair(int $itemId): void {
    try {
      $this->itemModel->repairItem($itemId);
      $this->flashMessage("messages.equipment.repaired");
    } catch(ItemNotFoundException | ItemNotOwnedException) {
      $this->redirect("Item:notfound");
    } catch(ItemNotDamagedException) {
      $this->flashMessage("errors.equipment.notDamaged");
    } catch(InsufficientFundsException) {
      $this->flashMessage("errors.equipment.notEnoughMoney");
    }
    $this->redirect("this");
  }
}
?>