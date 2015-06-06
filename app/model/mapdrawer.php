<?php
namespace HeroesofAbenez;

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
   * Draw local map
   * 
   * @return array
   */
  function localMap() {
    $stages = $this->locationModel->listOfStages();
    $curr_stage = $stages[$this->user->identity->stage];
    foreach($stages as $stage) {
      if($stage->area !== $curr_stage->area) unset($stages[$stage->id]);
      if($this->user->identity->level < $stage->required_level) unset($stages[$stage->id]);
      if(is_int($stage->required_race) AND $stage->required_race != $this->user->identity->race) unset($stages[$stage->id]);
      if(is_int($stage->required_occupation) AND $stage->required_occupation != $this->user->identity->occupation) unset($stages[$stage->id]);
    }
    $return = array(
      "stages" => $stages, "currentStage" => $curr_stage->id, "currentArea" => $curr_stage->area);
    return $return;
  }
}
?>