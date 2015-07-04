<?php
namespace HeroesofAbenez\Entities;

/**
 * One line of dialogue with a npc
 *
 * @author Jakub Konečný
 */
class DialogueLine extends \Nette\Object {
  /** @var string */
  protected $speaker;
  /** @var string */
  protected $text;
  /** @var array */
  protected $names = array();
  
  /**
   * @param string $speaker
   * @param string $text
   * @param array $names
   */
  function __construct($speaker, $text, array $names) {
    $speaker = strtolower($speaker);
    if($speaker == "player" OR $speaker == "npc") $this->speaker = $speaker;
    $this->text = $text;
    $this->names = $names;
  }
  
  /**
   * @return string
   */
  function getText() {
    $replace = array("#npcName#", "#playerName#");
    return str_replace($replace, $this->names, $this->text);
  }
  
  /**
   * @return string
   */
  function getSpeaker() {
    if($this->speaker === "npc") return $this->names[0];
    elseif($this->speaker === "player") return $this->names[1];
  }
}
?>