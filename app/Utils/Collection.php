<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

use Nette\OutOfRangeException,
    Nette\InvalidArgumentException;

/**
 * Collection
 * 
 * @author Jakub Konečný
 */
class Collection  implements \ArrayAccess, \Countable, \IteratorAggregate {
  /** @var array Items in the collection */
  protected $items = [];
  /** @var string Type of items in the collection */
  protected $class;
  
  /**
   * @return int
   */
  function count(): int {
    return count($this->items);
  }
  
  /**
   * @return \ArrayIterator
   */
  function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->items);
  }
  
  /**
   * @param int $index
   * @return bool
   */
  function offsetExists($index): bool {
    return $index >= 0 AND $index < count($this->items);
  }
  
  /**
   * @param int $index
   * @throws OutOfRangeException
   */
  function offsetGet($index) {
    if($index < 0 OR $index >= count($this->items)) {
      throw new OutOfRangeException("Offset invalid or out of range.");
    }
    return $this->items[$index];
  }
  
  /**
   * @param int $index
   * @param object $member
   * @return void
   * @throws OutOfRangeException
   * @throws InvalidArgumentException
   */
  function offsetSet($index, $member): void {
    if(!$member instanceof $this->class) {
      throw new InvalidArgumentException("Argument must be of $this->class type.");
    }
    if($index === NULL) {
      $this->items[] = $member;
    } elseif($index < 0 OR $index >= count($this->items)) {
      throw new OutOfRangeException("Offset invalid or out of range.");
    } else {
      $this->items[$index] = & $member;
    }
  }
  
  /**
   * @param int $index
   * @return void
   */
  function offsetUnset($index): void {
    if($index < 0 OR $index >= count($this->items)) {
      throw new OutOfRangeException("Offset invalid or out of range.");
    }
    array_splice($this->items, (int) $index, 1);
  }
}
?>