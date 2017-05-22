<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use Kdyby\Translation\Translator,
    HeroesofAbenez\Orm\NpcDummy,
    HeroesofAbenez\Orm\ItemDummy,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Shop Control
 *
 * @author Jakub Konečný
 * @property-write NpcDummy $npc
 */
class NPCShopControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var ORM */
  protected $orm;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var NpcDummy */
  protected $npc;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  
  function __construct(\Nette\Database\Context $db, ORM $orm, \HeroesofAbenez\Model\Item $itemModel, \Nette\Security\User $user, Translator $translator) {
    parent::__construct();
    $this->db = $db;
    $this->orm = $orm;
    $this->itemModel = $itemModel;
    $this->user = $user;
    $this->translator = $translator;
  }
  
  function setNpc(NpcDummy $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Get items in npc's shop
   * 
   * @return ItemDummy[]
   */
  function getItems(): array {
    $return = [];
    $items = $this->orm->shopItems->findByNpc($this->npc->id);
    foreach($items as $item) {
      $return[] = $this->itemModel->view($item->item->id);
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
    return (!is_null($row));
  }
  
  /**
   * Buy an item in the shop
   * 
   * @param int $itemId Item's id
   * @return void
   */
  function handleBuy(int $itemId): void {
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
?>