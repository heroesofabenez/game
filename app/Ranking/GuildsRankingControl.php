<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

use HeroesofAbenez\Orm\GuildDummy,
    HeroesofAbenez\Utils\Arrays;


/**
 * Guilds Ranking Control
 *
 * @author Jakub Konečný
 */
class GuildsRankingControl extends RankingControl {
  /** @var \HeroesofAbenez\Model\Guild */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Guild $model
   */
  function __construct(\HeroesofAbenez\Model\Guild $model) {
    $this->model = $model;
    parent::__construct("Guilds", ["name", "members"], "Guild", "Details");
  }
  
  /**
   * @return array
   */
  function getData(): array {
    $return = $data = [];
    $result = $this->model->listOfGuilds();
    foreach($result as $row) {
      $data[] = $row->toArray();
    }
    $data2 = Arrays::orderby($data, "members", SORT_DESC, "id", SORT_ASC);
    foreach($data2 as $row2) {
      $return[] = new GuildDummy($row2["id"], $row2["name"], "", $row2["members"]);
    }
    return $return;
  }
}
?>