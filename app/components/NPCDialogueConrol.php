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

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 */
class NPCDialogueControl extends \Nette\Application\UI\Control {
  /** @var int */
  protected $position = 0;
  /** @var array */
  protected $lines = array();
  /** @var array */
  protected $names;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\NPC */
  protected $npc;
  
  function __construct(\Nette\Security\User $user) {
    $this->user = $user;
    $this->names = array("", $user->identity->name);
  }
  
  function setNpc(\HeroesofAbenez\NPC $npc) {
    $this->npc = $npc;
    $this->names[0] = $npc->name;
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
  
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcDialogue.latte");
    $template->npcName = $this->npc->name;
    $template->texts = array();
    $template->texts[] = new DialogueLine("npc", "Greetings, #playerName#. Can I help you with anything?", $this->names);
    $template->texts[] = new DialogueLine("player", "Hail, #npcName#. Not now but thank you.", $this->names);
    $template->render();
  }
}
?>