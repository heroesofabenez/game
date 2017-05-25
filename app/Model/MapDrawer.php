<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Image,
    HeroesofAbenez\Orm\QuestStageDummy;

/**
 * Map Drawer Model
 *
 * @author Jakub Konečný
 */
class MapDrawer {
  use \Nette\SmartObject;
  
  /** @var Location */
  protected $locationModel;
  /** @var \Nette\Security\User */
  protected $user;
  
  /**
   * @param Location $locationModel
   * @param \Nette\Security\User $user
   */
  function __construct(Location $locationModel, \Nette\Security\User $user) {
    $this->locationModel = $locationModel;
    $this->user = $user;
  }
  
  /**
   * Draws a map
   * 
   * @param QuestStageDummy[] $points
   * @param \stdClass[] $routes
   * @param string $name
   * @return void
   */
  function draw(array $points, array $routes, string $name): void {
    $image = Image::fromBlank(250, 250, Image::rgb(204, 204, 153));
    $image->rectangle(0, 0, 249, 249, Image::rgb(204, 102, 0));
    /** @var QuestStageDummy $point */
    foreach($points as $point) {
      $image->filledEllipse($point->posX, $point->posY, 4, 4, Image::rgb(51, 102, 0));
      $image->ttfText(8, 0, $point->posX-18, $point->posY+11, Image::rgb(51, 51, 0), __DIR__ . "/../arial.ttf", $point->name);
    }
    /** @var \stdClass $route */
    foreach($routes as $route) {
      $image->line($points[$route->from]->posX, $points[$route->from]->posY, $points[$route->to]->posX, $points[$route->to]->posY, Image::rgb(51, 153, 255));
    }
    $filename = __DIR__ . "/../../images/maps/$name.jpeg";
    $image->save($filename);
  }
  
  /**
   * Draw local map
   * 
   * @return void
   */
  function localMap(): void {
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $curr_stage = $stages[$this->user->identity->stage];
    $routes = $this->locationModel->stageRoutes();
    $this->draw($stages, $routes, "local-$curr_stage->area");
  }
}
?>