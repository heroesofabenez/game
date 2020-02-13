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
  protected array $settings = [];
  
  use \Nette\SmartObject;
  
  public function __construct(array $settings) {
    $this->settings = $settings;
  }
  
  protected function getSettings(): array {
    return $this->settings;
  }
}
?>