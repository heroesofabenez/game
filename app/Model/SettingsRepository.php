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
  
  function __construct(array $settings) {
    $this->settings = $settings;
  }
  
  function getSettings(): array {
    return $this->settings;
  }
}
?>