<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use Nette\Localization\ITranslator;
use HeroesofAbenez\Orm\Item;
use HeroesofAbenez\Orm\Npc;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @property-write Npc $npc
 */
final class NPCShopControl extends \Nette\Application\UI\Control {
  protected ORM $orm;
  protected \HeroesofAbenez\Model\Item $itemModel;
  protected Npc $npc;
  protected \Nette\Security\User $user;
  protected ITranslator $translator;
  
  public function __construct(ORM $orm, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user, ITranslator $translator) {
    $this->orm = $orm;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->translator = $translator;
  }
  
  protected function setNpc(Npc $npc): void {
    $this->npc = $npc;
  }
  
  /**
   * Get items in npc's shop
   * 
   * @return Item[]
   */
  public function getItems(): array {
    $return = [];
    $items = $this->npc->items;
    foreach($items as $item) {
      $return[] = $item->item;
    }
    return $return;
  }
  
  public function render(): void {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcShop.latte");
    $template->npcId = $this->npc->id;
    $template->items = $this->getItems();
    $template->render();
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