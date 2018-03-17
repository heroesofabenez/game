<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * ChatCharacter
 *
 * @author Jakub Konečný
 * @property-read int|string $id
 * @property-read string $name
 */
class ChatCharacter {
  use \Nette\SmartObject;
  
  /** @var int|string */
  protected $id;
  /** @var string */
  protected $name;
  
  /**
   * @param int|string $id
   */
  public function __construct($id, string $name) {
    $this->id = $id;
    $this->name = $name;
  }
  
  /**
   * @return int|string
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }
}
?>