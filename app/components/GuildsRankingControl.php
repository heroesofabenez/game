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
    parent::__construct("Guild", array("name", "members"), "Guild", "Details");
  }
  
  function getData() {
    $return = array();
    $result = $this->model->listOfGuilds();
    foreach($result as $row) {
      $data[] = (array) $row;
    }
    $data2 = Arrays::orderby($data, "members", SORT_DESC, "id", SORT_ASC);
    foreach($data2 as $row2) {
      $return[] = array("id" => $row2["id"], "name" => $row2["name"], "members" => $row2["members"]);
    }
    return $return;
  }
} 
?>