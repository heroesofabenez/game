<?php
namespace HeroesofAbenez;

/**
 * Description of map
 *
 * @author Jakub Konečný
 */
class Map extends \Nette\Object {
  /** @var \HeroesofAbenez\Location */
  protected $locationModel;
  /** @var \HeroesofAbenez\MapDrawer */
  protected $drawer;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\HeroesofAbenez\Location $locationModel, \Nette\Security\User $user, \HeroesofAbenez\MapDrawer $drawer) {
    $this->locationModel = $locationModel;
    $this->drawer = $drawer;
    $this->user = $user;
  }
  
  /**
   * Returns data for local map and draws it when necessary
   * 
   * @return array
   */
  function local() {
    $curr_stage = $this->user->identity->stage;
    $filename = WWW_DIR . "/images/maps/local-$curr_stage.jpeg";
    $return = array("image" => $filename);
    if(!file_exists($filename)) {
      $this->drawer->localMap();
    }
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    foreach($stages as $stage) {
      $c1 = $stage->x-15; $c2 = $stage->y-15; $c3 = $stage->x+15; $c4 = $stage->y+15;
      $return["areas"][] = (object) array(
        "href" => "", "shape" => "rect", "title" => $stage->name,
        "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id
      );
    }
    return $return;
  }
}
?>