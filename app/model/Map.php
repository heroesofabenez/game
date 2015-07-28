<?php
namespace HeroesofAbenez\Model;

/**
 * Description of map
 *
 * @author Jakub Konečný
 */
class Map extends \Nette\Object {
  /** @var \HeroesofAbenez\Model\Location */
  protected $locationModel;
  /** @var \HeroesofAbenez\Model\MapDrawer */
  protected $drawer;
  /** @var \Nette\Security\User */
  protected $user;
  
  /**
   * 
   * @param \HeroesofAbenez\Model\Location $locationModel
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Model\MapDrawer $drawer
   */
  function __construct(Location $locationModel, \Nette\Security\User $user, MapDrawer $drawer) {
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
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $curr_stage = $stages[$this->user->identity->stage];
    $filename = WWW_DIR . "/images/maps/local-$curr_stage->area.jpeg";
    $return = array("image" => $filename);
    if(!file_exists($filename)) {
      $this->drawer->localMap();
    }
    foreach($stages as $stage) {
      $c1 = $stage->pos_x-15; $c2 = $stage->pos_y-15; $c3 = $stage->pos_x+15; $c4 = $stage->pos_y+15;
      $return["areas"][] = (object) array(
        "href" => "", "shape" => "rect", "title" => $stage->name,
        "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id
      );
    }
    return $return;
  }
}
?>