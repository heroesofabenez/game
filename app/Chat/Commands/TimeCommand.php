<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use Nette\Localization\Translator;

/**
 * Chat Command Time
 *
 * @author Jakub Konečný
 */
final class TimeCommand extends \HeroesofAbenez\Chat\ChatCommand {
  private Translator $translator;

  public function __construct(Translator $translator) {
    $this->translator = $translator;
  }
  
  public function execute(): string {
    $time = date("Y-m-d H:i:s");
    return $this->translator->translate("messages.chat.currentTime", 0, ["time" => $time]);
  }
}
?>