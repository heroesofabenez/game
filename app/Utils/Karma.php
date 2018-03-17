<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

/**
 * Karma
 *
 * @author Jakub Konečný
 */
class Karma {
  use \Nette\StaticClass;
  
  public const KARMA_WHITE = "white";
  public const KARMA_NEUTRAL = "neutral";
  public const KARMA_DARK = "dark";
  
  /**
   * @return string[]
   */
  public static function getKarmas(): array {
    return Constants::getConstantsValues(static::class, "KARMA_");
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  protected static function validateKarma(string ...$values): void {
    $karmas = static::getKarmas();
    foreach($values as $value) {
      if(!in_array($value, $karmas, true)) {
        throw new \OutOfBoundsException("Invalid karma.");
      }
    }
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  public static function isSame(string $karma1, string $karma2): bool {
    static::validateKarma($karma1, $karma2);
    return ($karma1 === $karma2);
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  public static function getOpposite(string $karma): ?string {
    static::validateKarma($karma);
    switch($karma) {
      case static::KARMA_NEUTRAL:
        return NULL;
      case static::KARMA_WHITE:
        return static::KARMA_DARK;
      case static::KARMA_DARK:
        return static::KARMA_WHITE;
    }
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  public static function isOpposite(string $karma1, string $karma2): bool {
    static::validateKarma($karma1, $karma2);
    return ($karma2 === static::getOpposite($karma1));
  }
}
?>