<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Entities\DialogueLine;

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 * @property-read string[] $names
 * @property-write \HeroesofAbenez\Entities\NPC $npc
 */
class NPCDialogueControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  /**
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
  }
  
  /**
   * @return string[]
   */
  function getNames() {
    if(is_null($this->npc)) {
      $npcName = "";
    } else {
      $npcName = $this->npc->name;
    }
    $playerName = $this->user->identity->name;
    return [$npcName, $playerName];
  }
  
  /**
   * Gets texts for current npc
   * 
   * @return array
   * @todo make it depend on player's identity and npc   
   */
  protected function getTexts(): array {
    $texts = [];
    $texts[] = [
      ["npc", "Greetings, #playerName#. Can I help you with anything?"],
      ["player", "Hail, #npcName#. Not now but thank you."],
    ];
    $texts[] = [
      ["npc", "Hail, #playerName#. I am quite busy at the moment. Can you come some other time?"],
      ["player", "Of course."],
    ];
    $texts[] = [
      ["npc", "Oh, #playerName#, long time one see. How are you doing?"],
      ["player", "Hail, #npcName#. I am fine and you?"],
      ["npc", "Everything is alright here."],
    ];
    return $texts[rand(0, count($texts) - 1)];
  }
  
  /**
   * @return void
   */
  function render() {
    $this->template->setFile(__DIR__ . "/npcDialogue.latte");
    $this->template->npcName = $this->npc->name;
    $this->template->texts = [];
    $texts = $this->getTexts();
    foreach($texts as $text) {
      $this->template->texts[] = new DialogueLine($text[0], $text[1], $this->names);
    }
    $this->template->render();
  }
}

interface NPCDialogueControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCDialogueControl */
  function create();
}
?>