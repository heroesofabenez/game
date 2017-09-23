<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Item as ItemEntity,
    HeroesofAbenez\Orm\Model as ORM,
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
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->user = $user;
  }
  
  /**
   * Gets name of specified item
   */
  public function getItemName(int $id): string {
    $item = $this->view($id);
    if(is_null($item)) {
      return "";
    }
    return $item->name;
  }
  
  /**
   * Get info about specified item
   */
  public function view(int $id): ?ItemEntity {
    return $this->orm->items->getById($id);
  }
  
  /**
   * Check if player has specified item
   */
  public function haveItem(int $id, int $amount = 1): bool {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return false;
    } elseif($item->amount < $amount) {
      return false;
    }
    return true;
  }
  
  /**
   * Give the player item(s)
   */
  public function giveItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      $item = new CharacterItem();
      $this->orm->characterItems->attach($item);
      $item->character = $this->user->id;
      $item->item = $id;
      $item->amount = 0;
    }
    $item->amount += $amount;
    $this->orm->characterItems->persistAndFlush($item);
  }
  
  public function loseItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return;
    }
    $item->amount -= $amount;
    $this->orm->characterItems->persistAndFlush($item);
  }
}
?>