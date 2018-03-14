<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

use Nette\Utils\Strings;

/**
 * Constants
 *
 * @author Jakub Konečný
 */
class Constants {
  use \Nette\StaticClass;
  
  /**
   * Get values of all public constants from class $class whose name starts with $prefix
   *
   * @throws \ReflectionException
   */
  public static function getConstantsValues(string $class, string $prefix): array {
    $values = [];
    $constants = (new \ReflectionClass($class))->getConstants();
    foreach($constants as $name => $value) {
      if(Strings::startsWith($name, $prefix)) {
        $values[] = $value;
      }
    }
    return $values;
  }
}
?>