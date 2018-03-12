<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

/**
 * Numbers
 *
 * @author Jakub Konečný
 */
class Numbers {
  use \Nette\StaticClass;
  
  /**
   * Ensure that a number is within boundaries
   */
  public static function range(int $number, int $min, int $max): int {
    return min(max($number, $min), $max);
  }
}
?>