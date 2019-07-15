<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

/**
 * One line of dialogue with a npc
 *
 * @author Jakub Konečný
 * @property-read string $speaker
 * @property-read string $text
 * @property-read string[] $names
 */
final class DialogueLine {
  use \Nette\SmartObject;
  
  /** @var string */
  protected $speaker = "player";
  /** @var string */
  protected $text;
  /** @var string[] */
  protected $names = [];
  
  public function __construct(string $speaker, string $text, array $names) {
    $speaker = strtolower($speaker);
    if($speaker === "player" || $speaker === "npc") {
      $this->speaker = $speaker;
    }
    $this->text = $text;
    $this->names = $names;
  }
  
  public function getText(): string {
    $replace = ["#npcName#", "#playerName#"];
    return str_replace($replace, $this->names, $this->text);
  }
  
  public function getSpeaker(): string {
    if($this->speaker === "npc") {
      return $this->names[0];
    }
    return $this->names[1];
  }
  
  /**
   * @return string[]
   */
  public function getNames(): array {
    return $this->names;
  }
}
?>