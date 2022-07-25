<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use Nette\Localization\ITranslator;

/**
 * Chat Command Time
 *
 * @author Jakub Konečný
 */
final class TimeCommand extends \HeroesofAbenez\Chat\ChatCommand {
  private ITranslator $translator;

  public function __construct(ITranslator $translator) {
    $this->translator = $translator;
  }
  
  public function execute(): string {
    $time = date("Y-m-d H:i:s");
    return $this->translator->translate("messages.chat.currentTime", 0, ["time" => $time]);
  }
}
?>