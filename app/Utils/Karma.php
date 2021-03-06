<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

use Nexendrie\Utils\Constants;

/**
 * Karma
 *
 * @author Jakub Konečný
 */
final class Karma {
  use \Nette\StaticClass;
  
  public const KARMA_WHITE = "white";
  public const KARMA_NEUTRAL = "neutral";
  public const KARMA_DARK = "dark";
  public const THRESHOLD = 5;
  
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
        throw new \OutOfBoundsException("Invalid karma $value.");
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
      case static::KARMA_WHITE:
        return static::KARMA_DARK;
      case static::KARMA_DARK:
        return static::KARMA_WHITE;
      default:
        return null;
    }
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  public static function isOpposite(string $karma1, string $karma2): bool {
    static::validateKarma($karma1, $karma2);
    return ($karma2 === static::getOpposite($karma1));
  }
  
  public static function getPredominant(int $white, int $dark): string {
    if($white > $dark + static::THRESHOLD) {
      return static::KARMA_WHITE;
    } elseif($dark > $white + static::THRESHOLD) {
      return static::KARMA_DARK;
    }
    return static::KARMA_NEUTRAL;
  }
}
?>