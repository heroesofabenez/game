<?php
namespace HeroesofAbenez\Ranking;

use HeroesofAbenez\Utils\Arrays;


/**
 * Guilds Ranking Control
 *
 * @author Jakub Konečný
 */
class GuildsRankingControl extends RankingControl {
  /** @var \HeroesofAbenez\GuildModel */
  protected $model;
  
  function __construct(\HeroesofAbenez\GuildModel $model) {
    $this->model = $model;
    parent::__construct("Guild", array("Name", "Members", ""));
  }
  
  function getData() {
    $return = array();
    $result = $this->model->listOfGuilds();
    foreach($result as $row) {
      $data[] = (array) $row;
    }
    $data2 = Arrays::orderby($data, "members", SORT_DESC, "id", SORT_ASC);
    foreach($data2 as $row2) {
      $return[] = array($row2["name"], $row2["members"]);
    }
    return $return;
  }
} 
?>