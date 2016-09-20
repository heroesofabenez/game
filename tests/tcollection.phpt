<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert;

class Item {
  public $var;
  
  public function __construct($var) {
    $this->var = $var;
  }
}

class Collection implements \ArrayAccess, \Countable, \IteratorAggregate {
  use \HeroesofAbenez\Utils\TCollection;
  
  function __construct() {
    $this->class = Item::class;
  }
}

class TCollectionTest extends MT\TestCase  {
  protected $col;
  
  /**
   * @return void
   */
  function setUp() {
    $this->col = new Collection;
  }
  
  /**
   * @return void
   */
  function testCount() {
    Assert::same(0, count($this->col));
    $this->col[] = new Item(self::class);
    Assert::same(1, count($this->col));
  }
  
  /**
   * @return void
   */
  function testGetIterator() {
    for($i = 1; $i <= 5; $i++) {
      $this->col[] = new Item("value");
    }
    /** @var Item $item */
    foreach($this->col as $item) {
      Assert::same("value", $item->var);
    }
  }
  
  /**
   * @return void
   */
  function testOffsetExists() {
    Assert::false(isset($this->col[0]));
    $this->col[] = new Item(self::class);
    Assert::true(isset($this->col[0]));
  }
  
  /**
   * @return void
   */
  function testOffsetGet() {
    $this->col[] = new Item(self::class);
    $item = $this->col[0];
    Assert::type(Item::class, $item);
  }
  
  /**
   * @return void
   */
  function testOffsetUnset() {
    $this->col[] = new Item(self::class);
    unset($this->col[0]);
    Assert::false(isset($this->col[0]));
  }
}
?>