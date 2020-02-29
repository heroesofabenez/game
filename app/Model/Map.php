<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Localization\ITranslator;

/**
 * Map Model
 *
 * @author Jakub Konečný
 */
final class Map {
  use \Nette\SmartObject;

  protected Location $locationModel;
  protected MapDrawer $drawer;
  protected \Nette\Security\User $user;
  protected ITranslator $translator;
  
  public function __construct(Location $locationModel, \Nette\Security\User $user, MapDrawer $drawer, ITranslator $translator) {
    $this->locationModel = $locationModel;
    $this->drawer = $drawer;
    $this->user = $user;
    $this->translator = $translator;
  }
  
  /**
   * Returns data for local map and draws it when necessary
   */
  public function local(): array {
    $this->locationModel->user = $this->user;
    $stages = $this->locationModel->accessibleStages();
    $currentStage = $stages[$this->user->identity->stage];
    $filename = $this->drawer->getLocalMapFilename($currentStage->area->id);
    $return = ["image" => $filename];
    if(!file_exists($filename)) {
      $this->drawer->localMap();
    }
    foreach($stages as $stage) {
      $c1 = $stage->posX - 15;
      $c2 = $stage->posY - 15;
      $c3 = $stage->posX + 15;
      $c4 = $stage->posY + 15;
      $return["areas"][] = (object) [
        "href" => "", "shape" => "rect", "title" => $stage->name,
        "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id
      ];
    }
    return $return;
  }
}
?>