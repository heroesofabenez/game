<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Image;
use HeroesofAbenez\Orm\QuestStage;
use Nexendrie\Translation\ILoader;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\RoutesStage;

/**
 * Map Drawer Model
 *
 * @author Jakub Konečný
 */
final class MapDrawer {
  use \Nette\SmartObject;

  protected Location $locationModel;
  protected \Nette\Security\User $user;
  protected ILoader $loader;
  protected string $wwwDir;

  public function __construct(string $wwwDir, Location $locationModel, \Nette\Security\User $user, ILoader $loader) {
    $this->locationModel = $locationModel;
    $this->user = $user;
    $this->loader = $loader;
    $this->wwwDir = $wwwDir;
  }

  /**
   * Draws a map
   * 
   * @param QuestStage[] $points
   * @param ICollection|RoutesStage[] $routes
   */
  public function draw(array $points, ICollection $routes, string $filename, int $width, int $height): void {
    $image = Image::fromBlank($width, $height, Image::rgb(204, 204, 153));
    $image->rectangle(0, 0, $width - 1, $height - 1, Image::rgb(204, 102, 0));
    foreach($points as $point) {
      $image->filledEllipse($point->posX, $point->posY, 4, 4, Image::rgb(51, 102, 0));
      $image->ttfText(8, 0, $point->posX - 18, $point->posY + 11, Image::rgb(51, 51, 0), __DIR__ . "/../arial.ttf", $point->name);
    }
    foreach($routes as $route) {
      $image->line($points[$route->from->id]->posX, $points[$route->from->id]->posY, $points[$route->to->id]->posX, $points[$route->to->id]->posY, Image::rgb(51, 153, 255));
    }
    $image->save($filename);
  }
  
  /**
   * Draw local map
   */
  public function localMap(): void {
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $currentStage = $stages[$this->user->identity->stage];
    $routes = $this->locationModel->stageRoutes($currentStage->area);
    $this->draw($stages, $routes, $this->getLocalMapFilename($currentStage->area->id), 250, 250);
  }

  private function getMapsFolder(): string {
    return $this->wwwDir . "/images/maps";
  }

  public function getLocalMapFilename(int $areaId): string {
    return $this->getMapsFolder() . "/local-$areaId-{$this->loader->getLang()}.jpeg";
  }
}
?>