<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Model\NpcPersonalityChooser;
use HeroesofAbenez\Orm\Npc;
use Nette\Localization\ITranslator;
use Nexendrie\Translation\ILoader;
use Nette\Utils\Strings;

/**
 * A set of dialogue lines, simplify working with them
 * 
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 * @property-read string[] $names
 */
final class NPCDialogueControl extends \Nette\Application\UI\Control {
  protected \Nette\Security\User $user;
  protected ITranslator $translator;
  protected ILoader $loader;
  protected NpcPersonalityChooser $npcPersonalityChooser;
  public ?Npc $npc = null;
  
  public function __construct(\Nette\Security\User $user, ITranslator $translator, ILoader $loader, NpcPersonalityChooser $npcPersonalityChooser) {
    $this->user = $user;
    $this->translator = $translator;
    $this->loader = $loader;
    $this->npcPersonalityChooser = $npcPersonalityChooser;
  }
  
  /**
   * @return string[]
   */
  protected function getNames() {
    $playerName = $this->user->identity->name;
    return [$this->npc->name ?? "", $playerName];
  }

  protected function getNpcMood(): string {
    /** @var Npc $npc */
    $npc = $this->npc;
    $personality = $this->npcPersonalityChooser->getPersonality($npc);
    return $personality->getMood($this->user->identity, $npc);
  }

  /**
   * Gets texts for current npc
   */
  protected function getTexts(): array {
    $mood = $this->getNpcMood();
    $texts = $this->loader->getTexts()["dialogues"][$mood];
    $texts = $texts[rand(0, count($texts) - 1)];
    array_walk($texts, function(&$value): void {
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
    $this->template->npc = $this->npc;
    $this->template->npcName = $this->names[0];
    $this->template->texts = [];
    $texts = $this->getTexts();
    foreach($texts as $text) {
      $this->template->texts[] = new DialogueLine($text["speaker"], $text["message"], $this->names);
    }
    $this->template->render();
  }
}
?>