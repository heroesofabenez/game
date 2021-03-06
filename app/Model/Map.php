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
    if(!file_exists($filename)) {
      $this->drawer->localMap();
    }
    $return = ["image" => realpath($filename)];
    foreach($stages as $stage) {
      $posX = $stage->posX ?? 0;
      $posY = $stage->posY ?? 0;
      $c1 = $posX - 15;
      $c2 = $posY - 15;
      $c3 = $posX + 15;
      $c4 = $posY + 15;
      $return["areas"][] = (object) [
        "href" => "", "shape" => "rect", "title" => $stage->name,
        "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id
      ];
    }
    return $return;
  }
}
?>