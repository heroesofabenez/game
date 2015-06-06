<?php
namespace HeroesofAbenez;

use Nette\Utils\Image;

/**
 * Map Drawer Model
 *
 * @author Jakub Konečný
 */
class MapDrawer extends \Nette\Object {
  /** @var \HeroesofAbenez\Location */
  protected $locationModel;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\HeroesofAbenez\Location $locationModel, \Nette\Security\User $user) {
    $this->locationModel = $locationModel;
    $this->user = $user;
  }
  
  /**
   * Draws a map
   * 
   * @param array $points
   * @param array $routes
   * @param int $id
   * @return void
   */
  function draw(array $points, array $routes, $id) {
    $image = Image::fromBlank(250, 250, Image::rgb(204, 204, 153));
    $image->rectangle(0, 0, 249, 249, Image::rgb(204, 102, 0));
    foreach($points as $point) {
      $image->filledellipse($point->x, $point->y, 4, 4, Image::rgb(51, 102, 0));
      $image->ttftext(8, 0, $point->x-18, $point->y+11, Image::rgb(51, 51, 0), "./app/ARIAL.ttf", $point->name);
    }
    foreach($routes as $route) {
      $image->line($points[$route->from]->x, $points[$route->from]->y, $points[$route->to]->x, $points[$route->to]->y, Image::rgb(51, 153, 255));
    }
    $filename = WWW_DIR . "/images/maps/local-$id.jpeg";
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
    $this->draw($stages, $routes, $curr_stage->area);
  }
}
?>