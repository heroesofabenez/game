<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * Settings Repository
 *
 * @author Jakub Konečný
 * @property-read array $settings
 */
class SettingsRepository {
  /** @var array */
  protected $settings = [];
  
  use \Nette\SmartObject;
  
  public function __construct(array $settings) {
    $this->settings = $settings;
  }
  
  public function getSettings(): array {
    return $this->settings;
  }
}
?>