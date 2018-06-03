<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

/**
 * Chat Command Time
 *
 * @author Jakub Konečný
 */
final class TimeCommand extends \HeroesofAbenez\Chat\ChatCommand {
  /** @var \Nette\Localization\ITranslator */
  protected $translator;
  
  public function __construct(\Nette\Localization\ITranslator $translator) {
    $this->translator = $translator;
  }
  
  public function execute(): string {
    $time = date("Y-m-d H:i:s");
    return $this->translator->translate("messages.chat.currentTime", 0, ["time" => $time]);
  }
}
?>