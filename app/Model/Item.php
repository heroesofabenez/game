<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\Item as ItemEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ItemDummy,
    HeroesofAbenez\Orm\CharacterItem;

/**
 * Item Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Application\LinkGenerator $linkGenerator
 */
class Item {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Application\LinkGenerator */
  protected $linkGenerator;
  
  function __construct(\Nette\Caching\Cache $cache, ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  function setLinkGenerator(\Nette\Application\LinkGenerator $generator) {
    $this->linkGenerator = $generator;
  }
  
  /**
   * Gets list of all items
   * 
   * @return ItemDummy[]
   */
  function listOfItems(): array {
    $items = $this->cache->load("items", function(& $dependencies) {
      $return = [];
      $items = $this->orm->items->findAll();
      /** @var ItemEntity $item */
      foreach($items as $item) {
        $return[$item->id] = new ItemDummy($item);
      }
      return $return;
    });
    return $items;
  }
  
  /**
   * Gets name of specified item
   * 
   * @param int $id Item's id
   * @return string
   */
  function getItemName(int $id): string {
    $item = $this->view($id);
    if(is_null($item)) {
      return "";
    } else {
      return $item->name;
    }
  }
  
  /**
   * Get info about specified item
   * 
   * @param int $id Item's id
   * @return ItemDummy|NULL
   */
  function view(int $id): ?ItemDummy {
    $items = $this->listOfItems();
    return Arrays::get($items, $id, NULL);
  }
  
  /**
   * Check if player has specified item
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return bool
   */
  function haveItem(int $id, int $amount = 1): bool {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return false;
    } elseif($item->amount < $amount) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Give the player item(s)
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return void
   */
  function giveItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      $item = new CharacterItem;
      $this->orm->characterItems->attach($item);
      $item->character = $this->user->id;
      $item->item = $id;
      $item->amount = $amount;
    } else {
      $item->amount += $amount;
    }
    $this->orm->characterItems->persistAndFlush($item);
  }
  
  /**
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return void
   */
  function loseItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return;
    }
    $item->amount -= $amount;
    $this->orm->characterItems->persistAndFlush($item);
  }
}
?>