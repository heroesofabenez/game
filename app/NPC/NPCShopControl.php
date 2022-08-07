<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Orm\CharacterItem;
use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
final class NPCShopControl extends \Nette\Application\UI\Control {
  private ORM $orm;
  private \HeroesofAbenez\Model\Item $itemModel;
  public Npc $npc;
  private \Nette\Security\User $user;
  
  public function __construct(ORM $orm, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->itemModel = $itemModel;
    $this->user = $user;
  }
  
  public function render(): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $this->template->setFile(__DIR__ . "/npcShop.latte");
    $this->template->npc = $this->npc;
    $items = [];
    foreach($this->npc->items as $item) {
      $characterItem = new CharacterItem();
      $characterItem->item = $item->item;
      $characterItem->character = $character;
      $items[] = $characterItem;
    }
    $this->template->items = $items;
    $this->template->money = $character->money;
    $this->template->render();
  }
  
  /**
   * Check if an item is in the shop
   */
  public function canBuyItem(int $id): bool {
    $row = $this->orm->shopItems->getByItemAndNpc($id, $this->npc->id);
    return ($row !== null);
  }
  
  /**
   * Buy an item in the shop
   */
  public function handleBuy(int $itemId): void {
    $item = $this->orm->items->getById($itemId);
    if($item === null) {
      $this->presenter->flashMessage("errors.shop.itemDoesNotExist");
      $this->presenter->redirect("this");
    }
    if(!$this->canBuyItem($itemId)) {
      $this->presenter->flashMessage("errors.shop.cannotBuyHere");
      $this->presenter->redirect("this");
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->money < $item->price) {
      $this->presenter->flashMessage("errors.shop.notEnoughMoney");
      $this->presenter->redirect("this");
    }
    $this->itemModel->giveItem($itemId, 1, true);
    $this->presenter->flashMessage("messages.shop.itemBought");
    $this->presenter->redirect("this");
  }
}
?>