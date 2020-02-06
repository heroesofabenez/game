<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

use HeroesofAbenez\Orm\Npc;
use Nexendrie\Translation\ILoader;
use Nette\Utils\Strings;
use HeroesofAbenez\Utils\Karma;

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
    if($this->npc !== null) {
      $npcName = $this->npc->name;
    }
    $playerName = $this->user->identity->name;
    return [$npcName, $playerName];
  }

  protected function getNpcMood(): string {
    $userKarma = Karma::getPredominant($this->user->identity->white_karma, $this->user->identity->dark_karma);
    $userLevel = $this->user->identity->level;
    $personality = $this->npc->personality;
    $npcKarma = $this->npc->karma;
    switch($personality) {
      case Npc::PERSONALITY_FRIENDLY:
      case Npc::PERSONALITY_CRAZY:
        if(Karma::isOpposite($npcKarma, $userKarma)) {
          return Npc::PERSONALITY_HOSTILE;
        }
        return $personality;
      case Npc::PERSONALITY_SHY:
        if(Karma::isOpposite($npcKarma, $userKarma)) {
          return Npc::PERSONALITY_RESERVED;
        }
        return $personality;
      case Npc::PERSONALITY_ELITIST:
        if($userLevel < $this->npc->level) {
          return Npc::PERSONALITY_HOSTILE;
        }
        return Npc::PERSONALITY_FRIENDLY;
      case Npc::PERSONALITY_TEACHING:
        if($userLevel > $this->npc->level) {
          return Npc::PERSONALITY_FRIENDLY;
        }
        return $personality;
      case Npc::PERSONALITY_RACIST:
        if($this->user->identity->race !== $this->npc->race->id) {
          return Npc::PERSONALITY_HOSTILE;
        }
        return Npc::PERSONALITY_CRAZY;
      case Npc::PERSONALITY_MISOGYNIST:
        if($this->user->identity->gender !== \HeroesofAbenez\Orm\Character::GENDER_MALE) {
          return Npc::PERSONALITY_HOSTILE;
        }
        return Npc::PERSONALITY_CRAZY;
      default:
        return $personality;
    }
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