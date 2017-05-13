<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

/**
 * Chat Command Time
 *
 * @author Jakub Konečný
 */
class TimeCommand extends \HeroesofAbenez\Chat\ChatCommand {
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  
  function __construct(\Kdyby\Translation\Translator $translator) {
    $this->translator = $translator;
  }
  
  /**
   * @return string
   */
  function execute(): string {
    $time = date("Y-m-d H:i:s");
    return $this->translator->translate("messages.chat.currentTime", ["time" => $time]);
  }
}
?>