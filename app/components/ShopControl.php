<?php
namespace HeroesofAbenez\NPC;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 */
class ShopControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\Model\Item $itemModel) {
    $this->db = $db;
    $this->itemModel = $itemModel;
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
    $template->setFile(__DIR__ . "/shop.latte");
    $template->npcName = $this->npc->name;
    $template->items = $this->getItems();
    $template->render();
  }
}
?>