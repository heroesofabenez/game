<?php
namespace HeroesofAbenez;

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
  
  /**
   * @param string $speaker
   * @param string $text
   * @param array $names
   */
  function __construct($speaker, $text, $names) {
    $speaker = strtolower($speaker);
    if($speaker == "player" OR $speaker == "npc") $this->speaker = $speaker;
    $replace = array("#npcName#", "#playerName#");
    $this->text = str_replace($replace, $names, $text);
  }
  
  /**
   * @param string $name
   * @return mixed
   */
  function &__get($name) {
    if(isset($this->$name)) return $this->$name;
  }
}
?>