<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use Nexendrie\Utils\Constants;

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

  private const SPEAKER_PLAYER = "player";
  private const SPEAKER_NPC = "npc";

  private string $speaker = self::SPEAKER_PLAYER;
  private string $text;
  /** @var string[] */
  private array $names;
  
  public function __construct(string $speaker, string $text, array $names) {
    $speaker = strtolower($speaker);
    if(in_array($speaker, Constants::getConstantsValues(static::class, "SPEAKER_"), true)) {
      $this->speaker = $speaker;
    }
    $this->text = $text;
    $this->names = $names;
  }
  
  protected function getText(): string {
    $replace = ["#npcName#", "#playerName#"];
    return str_replace($replace, $this->names, $this->text);
  }
  
  protected function getSpeaker(): string {
    if($this->speaker === "npc") {
      return $this->names[0];
    }
    return $this->names[1];
  }
  
  /**
   * @return string[]
   */
  protected function getNames(): array {
    return $this->names;
  }
}
?>