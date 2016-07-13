<?php
namespace HeroesofAbenez\Utils;

class Arrays {
  use \Nette\StaticClass;
  
  /**
   * @author jimpoz jimpoz@jimpoz.com
   * @license http://creativecommons.org/licenses/by/3.0/ CC-BY-3.0
   * @return array
   */
  static function orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
      if(is_string($field)) {
        $tmp = array();
        foreach ($data as $key => $row) {
          $tmp[$key] = $row[$field];
        }
        $args[$n] = $tmp;
      }
    }
    $args[] = &$data;
    call_user_func_array("array_multisort", $args);
    return array_pop($args);
  }
}

/**
 * Trait Collection
 * Target class must implement \ArrayAccess, \Countable, \IteratorAggregate 
 * 
 * @author Jakub Konečný
 */
trait TCollection {
  /** @var array Items in the collection */
  protected $items = array();
  /** @var string Type of items in the collection */
  protected $class;
  
  /**
   * @return int
   */
  function count() {
    return count($this->items);
  }
  
  /**
   * @return \ArrayIterator
   */
  function getIterator() {
    return new \ArrayIterator($this->items);
  }
  
  /**
   * @param int $index
   * @return bool
   */
  function offsetExists($index) {
    return $index >= 0 AND $index < count($this->items);
  }
  
  /**
   * @param int $index
   * @return Character
   * @throws \Nette\OutOfRangeException
   */
  function offsetGet($index) {
    if($index < 0 OR $index >= count($this->items)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    }
    return $this->items[(int) $index];
  }
  
  /**
   * @param int $index
   * @param \HeroesofAbenez\Entities\Character $member
   * @return void
   * @throws \Nette\OutOfRangeException
   */
  function offsetSet($index, $member) {
    if(!$member instanceof $this->class) throw new Nette\InvalidArgumentException("Argument must be of $this->class type.");
    if($index === NULL) {
      $this->items[] = $member;
    } elseif($index < 0 OR $index >= count($this->items)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    } else {
      $this->items[(int) $index] = $member;
    }
  }
  
  /**
   * @param int $index
   * @return void
   */
  function offsetUnset($index) {
    if($index < 0 OR $index >= count($this->items)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    }
    array_splice($this->items, (int) $index, 1);
  }
}
?>