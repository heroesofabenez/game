<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

use Nexendrie\Utils\Constants;

/**
 * Karma
 *
 * @author Jakub Konečný
 */
final class Karma
{
    use \Nette\StaticClass;

    public const string KARMA_WHITE = "white";
    public const string KARMA_NEUTRAL = "neutral";
    public const string KARMA_DARK = "dark";
    public const int THRESHOLD = 5;

    /**
     * @return string[]
     */
    public static function getKarmas(): array
    {
        return Constants::getConstantsValues(self::class, "KARMA_");
    }

    /**
     * @throws \OutOfBoundsException
     */
    private static function validateKarma(string ...$values): void
    {
        $karmas = self::getKarmas();
        foreach ($values as $value) {
            if (!in_array($value, $karmas, true)) {
                throw new \OutOfBoundsException("Invalid karma $value.");
            }
        }
    }

    /**
     * @throws \OutOfBoundsException
     */
    public static function isSame(string $karma1, string $karma2): bool
    {
        self::validateKarma($karma1, $karma2);
        return ($karma1 === $karma2);
    }

    /**
     * @throws \OutOfBoundsException
     */
    public static function getOpposite(string $karma): ?string
    {
        self::validateKarma($karma);
        return match ($karma) {
            self::KARMA_WHITE => self::KARMA_DARK,
            self::KARMA_DARK => self::KARMA_WHITE,
            default => null,
        };
    }

    /**
     * @throws \OutOfBoundsException
     */
    public static function isOpposite(string $karma1, string $karma2): bool
    {
        self::validateKarma($karma1, $karma2);
        return ($karma2 === self::getOpposite($karma1));
    }

    public static function getPredominant(int $white, int $dark): string
    {
        return match (true) {
            $white > $dark + self::THRESHOLD => self::KARMA_WHITE,
            $dark > $white + self::THRESHOLD => self::KARMA_DARK,
            default => self::KARMA_NEUTRAL,
        };
    }
}
