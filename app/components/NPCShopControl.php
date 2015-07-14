<?php
namespace HeroesofAbenez\NPC;

/**
 * Shop Control
 *
 * @author Jakub Konečný
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
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user) {
    $this->db = $db;
    $this->itemModel = $itemModel;
    $this->user = $user;
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Get items in npc's shop
   * 
   * @return array
   */
  function getItems() {
    $return = array();
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
    $template->npcName = $this->npc->name;
    $template->items = $this->getItems();
    $template->render();
  }
  
  /**
   * Check if an item is in the shop
   * 
   * @param int $id Item's id
   * @return bool
   */
  function canBuyItem($id) {
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
  function handleBuy($itemId) {
    $item = $this->itemModel->view($itemId);
    if(!$item) {
      $this->presenter->flashMessage("Specified item doesn't exist.");
      return;
    }
    if(!$this->canBuyItem($itemId)) {
      $this->presenter->flashMessage("You can't buy the item from this location.");
      return;
    }
    $character = $this->db->table("characters")->get($this->user->id);
    if($character->money < $item->price) {
      $this->presenter->flashMessage("You don't have enough money.");
      return;
    }
    $this->itemModel->giveItem($itemId);
    $data = "money=money-{$item->price}";
    $this->db->query("UPDATE characters SET $data WHERE id=?", $this->user->id);
    $this->presenter->flashMessage("Item bought.");
  }
}
?>