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
  /** @var array */
  protected $settings = [];
  
  use \Nette\SmartObject;
  
  public function __construct(array $settings) {
    $this->settings = $settings;
  }
  
  protected function getSettings(): array {
    return $this->settings;
  }
}
?>