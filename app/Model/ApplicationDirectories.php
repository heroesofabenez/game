<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

final class ApplicationDirectories {
  use \Nette\SmartObject;

  public function __construct(public readonly string $wwwDir, public readonly string $appDir) {
  }
}
?>