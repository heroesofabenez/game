<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Orm\Npc;
use Nexendrie\Translation\Loaders\ILoader;
use Nette\Utils\Strings;

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @property-read string[] $names
 * @property-write Npc $npc
 */
final class NPCDialogueControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ILoader */
  protected $loader;
  /** @var Npc|null */
  protected $npc = null;
  
  public function __construct(\Nette\Security\User $user, ILoader $loader) {
    parent::__construct();
    $this->user = $user;
    $this->loader = $loader;
  }
  
  public function setNpc(Npc $npc): void {
    $this->npc = $npc;
  }
  
  /**
   * @return string[]
   */
  public function getNames() {
    $npcName = "";
    if(!is_null($this->npc)) {
      $npcName = $this->npc->name;
    }
    $playerName = $this->user->identity->name;
    return [$npcName, $playerName];
  }
  
  /**
   * Gets texts for current npc
   *
   * @todo make it depend on player's identity and npc
   */
  protected function getTexts(): array {
    $personality = "friendly";
    $texts = $this->loader->getTexts()["dialogues"][$personality];
    $texts = $texts[rand(0, count($texts) - 1)];
    array_walk($texts, function(&$value) {
      $speaker = Strings::before($value, ": ");
      $message = Strings::after($value, ": ");
      $value = [
        "speaker" => $speaker, "message" => $message,
      ];
    });
    return $texts;
  }
  
  public function render(): void {
    $this->template->setFile(__DIR__ . "/npcDialogue.latte");
    $this->template->npcName = $this->npc->name;
    $this->template->texts = [];
    $texts = $this->getTexts();
    foreach($texts as $text) {
      $this->template->texts[] = new DialogueLine($text["speaker"], $text["message"], $this->names);
    }
    $this->template->render();
  }
}
?>