<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use Kdyby\Translation\Translator;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-write \HeroesofAbenez\Entities\NPC $npc
 */
class NPCShopControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user, Translator $translator) {
    $this->db = $db;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->translator = $translator;
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Get items in npc's shop
   * 
   * @return \HeroesofAbenez\Entities\Item[]
   */
  function getItems() {
    $return = [];
    $items = $this->db->table("shop_items")
      ->where("npc", $this->npc->id)
      ->order("order");
    foreach($items as $item) {
      $return[] = $this->itemModel->view($item->item);
    }
    return $return;
  }
  
  /**
   * @return void
   */
  function render() {
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
    $row = $this->db->table("shop_items")
      ->where("npc", $this->npc->id)
      ->where("item", $id);
    if($row->count("*") > 0) return true;
    else return false;
  }
  
  /**
   * Buy an item in the shop
   * 
   * @param int $itemId Item's id
   * @return void
   */
  function handleBuy(int $itemId) {
    $item = $this->itemModel->view($itemId);
    if(!$item) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.itemDoesNotExist"));
      return;
    }
    if(!$this->canBuyItem($itemId)) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.cannotBuyHere"));
      return;
    }
    $character = $this->db->table("characters")->get($this->user->id);
    if($character->money < $item->price) {
      $this->presenter->flashMessage($this->translator->translate("errors.shop.notEnoughMoney"));
      return;
    }
    $this->itemModel->giveItem($itemId);
    $data = "money=money-{$item->price}";
    $this->db->query("UPDATE characters SET $data WHERE id=?", $this->user->id);
    $this->presenter->flashMessage($this->translator->translate("messages.shop.itemBought"));
  }
}

interface NPCShopControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCQuestsControl */
  function create();
}
?>