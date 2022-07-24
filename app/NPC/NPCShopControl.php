<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
final class NPCShopControl extends \Nette\Application\UI\Control {
  protected ORM $orm;
  protected \HeroesofAbenez\Model\Item $itemModel;
  public Npc $npc;
  protected \Nette\Security\User $user;
  
  public function __construct(ORM $orm, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->itemModel = $itemModel;
    $this->user = $user;
  }
  
  public function render(): void {
    $this->template->setFile(__DIR__ . "/npcShop.latte");
    $this->template->npc = $this->npc;
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
    $this->itemModel->giveItem($itemId);
    $character->money -= (int) ($item->price - $item->price / 100 * $character->charismaBonus);
    $character->lastActive = new \DateTimeImmutable();
    $this->orm->characters->persistAndFlush($character);
    $this->presenter->flashMessage("messages.shop.itemBought");
    $this->presenter->redirect("this");
  }
}
?>