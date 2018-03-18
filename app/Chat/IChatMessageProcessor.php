<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * IChatMessageProcessor
 *
 * @author Jakub Konečný
 */
interface IChatMessageProcessor {
  /**
   * @return null|string The result/null if the processor is not applicable
   */
  public function parse(string $message): ?string;
}
?>