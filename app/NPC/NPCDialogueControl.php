<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Entities\DialogueLine;

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 * @property-write \HeroesofAbenez\Entities\NPC $npc
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
    $this->names = ["", $user->identity->name];
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
  protected function getTexts(): array {
    $texts = [
      ["npc", "Greetings, #playerName#. Can I help you with anything?"],
      ["player", "Hail, #npcName#. Not now but thank you."]
    ];
    return $texts;
  }
  
  /**
   * Adds new line
   * 
   * @param string $speaker
   * @param string $text
   * @return DialogueLine
   */
  function newLine(string $speaker, string $text): DialogueLine {
    return new DialogueLine($speaker, $text, $this->names);
  }
  
  /**
   * @return void
   */
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcDialogue.latte");
    $template->npcName = $this->npc->name;
    $template->texts = [];
    $texts = $this->getTexts();
    foreach($texts as $text) {
      $template->texts[] = $this->newLine($text[0], $text[1]);
    }
    $template->render();
  }
}

interface NPCDialogueControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCDialogueControl */
  function create();
}
?>