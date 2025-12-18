<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * Map Model
 *
 * @author Jakub Konečný
 */
final class Map
{
    private readonly bool $alwaysDraw;

    public function __construct(private readonly Location $locationModel, private readonly \Nette\Security\User $user, private readonly MapDrawer $drawer, SettingsRepository $sr)
    {
        $this->alwaysDraw = $sr->settings["application"]["alwaysDrawMaps"];
    }

    /**
     * Returns data for local map and draws it when necessary
     */
    public function local(): array
    {
        $this->locationModel->user = $this->user;
        $stages = $this->locationModel->accessibleStages();
        $currentStage = $stages[$this->user->identity->stage];
        $filename = $this->drawer->getLocalMapFilename($currentStage->area->id);
        if ($this->alwaysDraw || !file_exists($filename)) {
            $this->drawer->localMap();
        }
        $return = ["image" => realpath($filename)];
        foreach ($stages as $stage) {
            $posX = $stage->posX ?? 0;
            $posY = $stage->posY ?? 0;
            $c1 = $posX - 15;
            $c2 = $posY - 15;
            $c3 = $posX + 15;
            $c4 = $posY + 15;
            $return["areas"][] = (object) [
                "href" => "", "shape" => "rect", "title" => $stage->name,
                "coords" => "$c1,$c2,$c3,$c4", "stage" => $stage->id,
            ];
        }
        return $return;
    }

    public function global(): array
    {
        $areas = $this->locationModel->accessibleAreas();
        $filename = $this->drawer->getGlobalMapFilename();
        if ($this->alwaysDraw || !file_exists($filename)) {
            $this->drawer->globalMap();
        }
        $return = ["image" => realpath($filename)];
        foreach ($areas as $area) {
            $posX = $area->posX ?? 0;
            $posY = $area->posY ?? 0;
            $c1 = $posX - 15;
            $c2 = $posY - 15;
            $c3 = $posX + 15;
            $c4 = $posY + 15;
            $return["areas"][] = (object) [
                "href" => "", "shape" => "rect", "title" => $area->name,
                "coords" => "$c1,$c2,$c3,$c4", "area" => $area->id,
            ];
        }
        return $return;
    }
}
