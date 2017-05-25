<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for item
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read string $image
 * @property-read int $price
 */
class ItemDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var string */
  protected $image;
  /** @var int */
  protected $price;
  
  function __construct(Item $item) {
    $this->id = $item->id;
    $this->name = $item->name;
    $this->description = $item->description;
    $this->image = $item->image;
    $this->price = $item->price;
  }
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return string
   */
  function getDescription(): string {
    return $this->description;
  }
  
  /**
   * @return string
   */
  function getImage(): string {
    return $this->image;
  }
  
  /**
   * @return int
   */
  function getPrice(): int {
    return $this->price;
  }
}
?>