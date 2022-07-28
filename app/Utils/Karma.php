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
  private static function validateKarma(string ...$values): void {
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
    return match ($karma) {
      static::KARMA_WHITE => static::KARMA_DARK,
      static::KARMA_DARK => static::KARMA_WHITE,
      default => null,
    };
  }
  
  /**
   * @throws \OutOfBoundsException
   */
  public static function isOpposite(string $karma1, string $karma2): bool {
    static::validateKarma($karma1, $karma2);
    return ($karma2 === static::getOpposite($karma1));
  }
  
  public static function getPredominant(int $white, int $dark): string {
    return match (true) {
      $white > $dark + static::THRESHOLD => static::KARMA_WHITE,
      $dark > $white + static::THRESHOLD => static::KARMA_DARK,
      default => static::KARMA_NEUTRAL,
    };
  }
}
?>