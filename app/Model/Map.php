<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * Map Model
 *
 * @author Jakub Konečný
 */
class Map {
  use \Nette\SmartObject;
  
  /** @var \HeroesofAbenez\Model\Location */
  protected $locationModel;
  /** @var MapDrawer */
  protected $drawer;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(Location $locationModel, \Nette\Security\User $user, MapDrawer $drawer) {
    $this->locationModel = $locationModel;
    $this->drawer = $drawer;
    $this->user = $user;
  }
  
  /**
   * Returns data for local map and draws it when necessary
   */
  function local(): array {
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $curr_stage = $stages[$this->user->identity->stage];
    $filename = __DIR__ . "/../../images/maps/local-{$curr_stage->area->id}.jpeg";
    $return = ["image" => $filename];
    if(!file_exists($filename)) {
      $this->drawer->localMap();
    }
    foreach($stages as $stage) {
      $c1 = $stage->posX-15;
      $c2 = $stage->posY-15;
      $c3 = $stage->posX+15;
      $c4 = $stage->posY+15;
      $return["areas"][] = (object) [
        "href" => "", "shape" => "rect", "title" => $stage->name,
        "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id
      ];
    }
    return $return;
  }
}
?>