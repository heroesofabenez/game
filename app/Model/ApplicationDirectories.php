<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

final class ApplicationDirectories {
  use \Nette\SmartObject;

  // TODO: make the properties readonly once we drop support for PHP 8.0
  public function __construct(public string $wwwDir, public string $appDir) {
  }
}
?>