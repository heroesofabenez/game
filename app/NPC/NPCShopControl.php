<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use Nette\Localization\ITranslator,
    HeroesofAbenez\Orm\Item,
    HeroesofAbenez\Orm\Npc,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-write Npc $npc
 */
class NPCShopControl extends \Nette\Application\UI\Control {
  /** @var ORM */
  protected $orm;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var Npc */
  protected $npc;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ITranslator */
  protected $translator;
  
  function __construct(ORM $orm, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user, ITranslator $translator) {
    parent::__construct();
    $this->orm = $orm;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->translator = $translator;
  }
  
  function setNpc(Npc $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Get items in npc's shop
   * 
   * @return Item[]
   */
  function getItems(): array {
    $return = [];
    $items = $this->npc->items;
    foreach($items as $item) {
      $return[] = $item->item;
    }
    return $return;
  }
  
  /**
   * @return void
   */
  function render(): void {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcShop.latte");
    $template->npcId = $this->npc->id;
    $template->items = $this->getItems();
    $template->render();
  }
  
  /**
   * Check if an item is in the shop
   * 
   * @param int $id Item's id
   * @return bool
   */
  function canBuyItem(int $id): bool {
    $row = $this->orm->shopItems->getById($id);
    return (!is_null($row) AND $row->npc->id === $this->npc->id);
  }
  
  /**
   * Buy an item in the shop
   * 
   * @param int $itemId Item's id
   * @return void
   */
  function handleBuy(int $itemId): void {
    $item = $this->orm->items->getById($itemId);
    if(is_null($item)) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.itemDoesNotExist"));
      return;
    }
    if(!$this->canBuyItem($itemId)) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.cannotBuyHere"));
      return;
    }
    $character = $this->orm->characters->getById($this->user->id);
    if($character->money < $item->price) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.notEnoughMoney"));
      return;
    }
    $this->itemModel->giveItem($itemId);
    $character->money -= $item->price;
    $this->orm->characters->persistAndFlush($character);
    $this->presenter->flashMessage($this->translator->translate("messages.shop.itemBought"));
  }
}
?>