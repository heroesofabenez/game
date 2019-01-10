<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Image;
use HeroesofAbenez\Orm\QuestStage;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\RoutesStage;

/**
 * Map Drawer Model
 *
 * @author Jakub Konečný
 */
final class MapDrawer {
  use \Nette\SmartObject;
  
  /** @var Location */
  protected $locationModel;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(Location $locationModel, \Nette\Security\User $user) {
    $this->locationModel = $locationModel;
    $this->user = $user;
  }
  
  /**
   * Draws a map
   * 
   * @param QuestStage[] $points
   * @param ICollection|RoutesStage[] $routes
   */
  public function draw(array $points, ICollection $routes, string $name, int $width, int $height): void {
    $image = Image::fromBlank($width, $height, Image::rgb(204, 204, 153));
    $image->rectangle(0, 0, $width - 1, $height - 1, Image::rgb(204, 102, 0));
    foreach($points as $point) {
      $image->filledEllipse($point->posX, $point->posY, 4, 4, Image::rgb(51, 102, 0));
      $image->ttfText(8, 0, $point->posX - 18, $point->posY + 11, Image::rgb(51, 51, 0), __DIR__ . "/../arial.ttf", $point->name);
    }
    foreach($routes as $route) {
      $image->line($points[$route->from->id]->posX, $points[$route->from->id]->posY, $points[$route->to->id]->posX, $points[$route->to->id]->posY, Image::rgb(51, 153, 255));
    }
    $filename = __DIR__ . "/../../images/maps/$name.jpeg";
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
    $this->draw($stages, $routes, "local-{$currentStage->area->id}", 250, 250);
  }
}
?>