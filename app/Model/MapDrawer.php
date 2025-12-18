<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\QuestArea;
use HeroesofAbenez\Orm\RoutesArea;
use Nette\Utils\Image;
use HeroesofAbenez\Orm\QuestStage;
use Nexendrie\Translation\Loader;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\RoutesStage;

/**
 * Map Drawer Model
 *
 * @author Jakub Konečný
 */
final class MapDrawer
{
    public function __construct(private readonly Location $locationModel, private readonly \Nette\Security\User $user, private readonly Loader $loader, private readonly ApplicationDirectories $directories)
    {
    }

    /**
     * Draws a map
     *
     * @param QuestStage[]|QuestArea[] $points
     * @param ICollection|RoutesStage[]|RoutesArea[] $routes
     */
    private function draw(array $points, ICollection $routes, string $filename, int $width, int $height): void
    {
        // @phpstan-ignore argument.type, argument.type
        $image = Image::fromBlank($width, $height, Image::rgb(204, 204, 153));
        $image->rectangle(0, 0, $width - 1, $height - 1, Image::rgb(204, 102, 0));
        foreach ($points as $point) {
            $posX = $point->posX ?? 0;
            $posY = $point->posY ?? 0;
            $image->filledEllipse($posX, $posY, 4, 4, Image::rgb(51, 102, 0));
            $image->ttfText(8, 0, $posX - 18, $posY + 11, Image::rgb(51, 51, 0), __DIR__ . "/../arial.ttf", $point->name);
        }
        foreach ($routes as $route) {
            $image->line($points[$route->from->id]->posX, $points[$route->from->id]->posY, $points[$route->to->id]->posX, $points[$route->to->id]->posY, Image::rgb(51, 153, 255));
        }
        $image->save($filename);
    }

    /**
     * Draw local map
     */
    public function localMap(): void
    {
        $this->locationModel->user = $this->user;
        $stages = $this->locationModel->accessibleStages();
        $currentStage = $stages[$this->user->identity->stage];
        $routes = $this->locationModel->stageRoutes($currentStage->area);
        $this->draw($stages, $routes, $this->getLocalMapFilename($currentStage->area->id), 250, 250);
    }

    /**
     * Draw global map
     */
    public function globalMap(): void
    {
        $areas = $this->locationModel->accessibleAreas();
        $routes = $this->locationModel->areaRoutes();
        $this->draw($areas, $routes, $this->getGlobalMapFilename(), 450, 350);
    }

    private function getMapsFolder(): string
    {
        return $this->directories->wwwDir . "/images/maps";
    }

    public function getLocalMapFilename(int $areaId): string
    {
        return $this->getMapsFolder() . "/local-$areaId-{$this->loader->getLang()}.jpeg";
    }

    public function getGlobalMapFilename(): string
    {
        return $this->getMapsFolder() . "/global-{$this->loader->getLang()}.jpeg";
    }
}
