<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * ChatMessage
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $message
 * @property-read string $when
 * @property-read ChatCharacter $character
 */
class ChatMessage {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $message;
  /** @var string */
  protected $when;
  /** @var ChatCharacter */
  protected $character;
  
  public function __construct(int $id, string $message, string $when, ChatCharacter $character) {
    $this->id = $id;
    $this->message = $message;
    $this->when = $when;
    $this->character = $character;
  }
  
  public function getId(): int {
    return $this->id;
  }
  
  public function getMessage(): string {
    return $this->message;
  }
  
  public function getWhen(): string {
    return $this->when;
  }
  
  public function getCharacter(): ChatCharacter {
    return $this->character;
  }
}
?>