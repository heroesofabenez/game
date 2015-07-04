<?php
namespace HeroesofAbenez\Dialogue;

use HeroesofAbenez\Entities\DialogueLine;

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 */
class NPCDialogueControl extends \Nette\Application\UI\Control {
  /** @var array */
  protected $names;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  /**
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Security\User $user) {
    $this->user = $user;
    $this->names = array("", $user->identity->name);
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
    $this->names[0] = $npc->name;
  }
  
  /**
   * Gets texts for current npc
   * 
   * @return array
   * @todo make it depend on player's identity and npc   
   */
  protected function getTexts() {
    $texts = array(
      array("npc", "Greetings, #playerName#. Can I help you with anything?"),
      array("player", "Hail, #npcName#. Not now but thank you.")
    );
    return $texts;
  }
  
  /**
   * Adds new line
   * 
   * @param string $speaker
   * @param string $text
   * @return \HeroesofAbenez\DialogueLine
   */
  function newLine($speaker, $text) {
    return new DialogueLine($speaker, $text, $this->names);
  }
  
  /**
   * @return void
   */
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcDialogue.latte");
    $template->npcName = $this->npc->name;
    $template->texts = array();
    $texts = $this->getTexts();
    foreach($texts as $text) {
      $template->texts[] = $this->newLine($text[0], $text[1]);
    }
    $template->render();
  }
}
?>