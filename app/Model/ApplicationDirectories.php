<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * @property-read string $wwwDir
 * @property-read string $appDir
 */
final class ApplicationDirectories {
  use \Nette\SmartObject;

  private string $wwwDir;
  private string $appDir;

  public function __construct(string $wwwDir, string $appDir) {
    $this->wwwDir = $wwwDir;
    $this->appDir = $appDir;
  }

  protected function getWwwDir(): string {
    return $this->wwwDir;
  }

  protected function getAppDir(): string {
    return $this->appDir;
  }
}
?>