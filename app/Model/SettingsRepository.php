<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * Settings Repository
 *
 * @author Jakub Konečný
 */
final readonly class SettingsRepository
{
    public function __construct(public array $settings)
    {
    }
}
