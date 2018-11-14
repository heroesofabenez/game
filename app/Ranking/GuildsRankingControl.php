<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

use HeroesofAbenez\Utils\Arrays;

/**
 * Guilds Ranking Control
 *
 * @author Jakub Konečný
 */
final class GuildsRankingControl extends RankingControl {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  
  public function __construct(\HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
    parent::__construct("Guilds", ["name", "members"], "Guild", "details");
  }
  
  /**
   * @return \stdClass[]
   */
  public function getData(): array {
    $return = $data = [];
    $result = $this->model->listOfGuilds();
    foreach($result as $row) {
      $data[] = ["id" => $row->id, "name" =>  $row->name, "description" => $row->description, "members" => $row->members->countStored(),];
    }
    $data2 = Arrays::orderby($data, "members", SORT_DESC, "id", SORT_ASC);
    foreach($data2 as $row2) {
      $return[] = (object) ["id" => $row2["id"], "name" => $row2["name"], "members" => $row2["members"]];
    }
    return $return;
  }
}
?>