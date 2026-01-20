<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

final readonly class ApplicationDirectories
{
    public function __construct(public string $wwwDir, public string $appDir)
    {
    }
}
