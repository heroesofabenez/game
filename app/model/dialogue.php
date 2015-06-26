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
  /** @var array */
  protected $names = array();
  
  /**
   * @param string $speaker
   * @param string $text
   * @param array $names
   */
  function __construct($speaker, $text, $names) {
    $speaker = strtolower($speaker);
    if($speaker == "player" OR $speaker == "npc") $this->speaker = $speaker;
    $this->text = $text;
    $this->names = $names;
  }
  
  /**
   * @return string
   */
  function getSpeaker() {
    if($this->speaker === "npc") return $this->names[0];
    elseif($this->speaker === "player") return $this->names[1];
  }
  
  /**
   * @return string
   */
  function getText() {
    $replace = array("#npcName#", "#playerName#");
    return str_replace($replace, $this->names, $this->text);
  }
}

/**
 * A set of dialogue lines, simplify working with them. Behaves like an array
 * 
 * @author Jakub Konečný
 */
class Dialogue extends \Nette\Object implements \Iterator, \Countable {
  /** @var int */
  protected $position = 0;
  /** @var array */
  protected $lines = array();
  /** @var array */
  protected $names;
  
  function __construct(array $names) {
    $this->names = $names;
  }
  
  /**
   * @return void
   */
  function rewind() {
    $this->position = 0;
  }
  
  /**
   * @return void
   */
  function current() {
    return $this->lines[$this->position];
  }
  
  /**
   * @return void
   */
  function key() {
    return $this->position;
  }
  
  /**
   * @return void
   */
  function next() {
    ++$this->position;
  }
  
  /**
   * @return void
   */
  function valid() {
    return isset($this->lines[$this->position]);
  }
  
  /**
   * @return int
   */
  function count() {
    return count($this->lines);
  }
  
  /**
   * Adds new line
   * 
   * @param string $speaker
   * @param string $text
   * @return int New line's index
   */
  function addLine($speaker, $text) {
    $count = count($this);
    $this->lines[$count] = new DialogueLine($speaker, $text, $this->names);
    return $count;
  }
  
  /**
   * Removes specified line
   * 
   * @param int $index Line's index
   * @return void
   */
  function removeLine($index) {
    if(isset($this->lines[$index])) unset($this->lines[$index]);
  }
}
?>