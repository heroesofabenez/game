<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Image;

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
   * @param array $points
   * @param array $routes
   * @param string $name
   * @return void
   */
  function draw(array $points, array $routes, $name) {
    $image = Image::fromBlank(250, 250, Image::rgb(204, 204, 153));
    $image->rectangle(0, 0, 249, 249, Image::rgb(204, 102, 0));
    foreach($points as $point) {
      $image->filledEllipse($point->pos_x, $point->pos_y, 4, 4, Image::rgb(51, 102, 0));
      $image->ttfText(8, 0, $point->pos_x-18, $point->pos_y+11, Image::rgb(51, 51, 0), APP_DIR . "/arial.ttf", $point->name);
    }
    foreach($routes as $route) {
      $image->line($points[$route->from]->pos_x, $points[$route->from]->pos_y, $points[$route->to]->pos_x, $points[$route->to]->pos_y, Image::rgb(51, 153, 255));
    }
    $filename = WWW_DIR . "/images/maps/$name.jpeg";
    $image->save($filename);
  }
  
  /**
   * Draw local map
   * 
   * @return void
   */
  function localMap() {
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $curr_stage = $stages[$this->user->identity->stage];
    $routes = $this->locationModel->stageRoutes();
    $this->draw($stages, $routes, "local-$curr_stage->area");
  }
}
?>