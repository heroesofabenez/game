<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * One line of dialogue with a npc
 *
 * @author Jakub Konečný
 * @property-read string $speaker
 * @property-read string $text
 * @property-read string[] $names
 */
class DialogueLine {
  use \Nette\SmartObject;
  
  /** @var string */
  protected $speaker;
  /** @var string */
  protected $text;
  /** @var string[] */
  protected $names = [];
  
  /**
   * @param string $speaker
   * @param string $text
   * @param array $names
   */
  function __construct(string $speaker, string $text, array $names) {
    $speaker = strtolower($speaker);
    if($speaker == "player" OR $speaker == "npc") {
      $this->speaker = $speaker;
    }
    $this->text = $text;
    $this->names = $names;
  }
  
  /**
   * @return string
   */
  function getText(): string {
    $replace = ["#npcName#", "#playerName#"];
    return str_replace($replace, $this->names, $this->text);
  }
  
  /**
   * @return string
   */
  function getSpeaker(): string {
    if($this->speaker === "npc") {
      return $this->names[0];
    } elseif($this->speaker === "player") {
      return $this->names[1];
    }
  }
  
  /**
   * @return string[]
   */
  function getNames(): array {
    return $this->names;
  }
}
?>