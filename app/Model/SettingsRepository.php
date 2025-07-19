<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * Settings Repository
 *
 * @author Jakub Konečný
 * @property-read array $settings
 */
final class SettingsRepository {
  use \Nette\SmartObject;
  
  public function __construct(private array $settings) {
  }
  
  protected function getSettings(): array {
    return $this->settings;
  }
}
?>