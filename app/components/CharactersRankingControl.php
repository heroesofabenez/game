<?php
namespace HeroesofAbenez\Ranking;

class CharactersRankingControl extends RankingControl {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\GuildModel */
  protected $guildModel;
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\GuildModel $guildModel) {
    $this->db = $db;
    $this->guildModel = $guildModel;
    parent::__construct("Characters", array("name", "level", "guild"), "Profile", "Profile");
  }
  
  function getData() {
    $characters = $this->db->table("characters")->order("level, experience, id")
      ->limit($this->paginator->getLength(), $this->paginator->getOffset());
    $result = $this->db->table("characters");
    $this->paginator->itemCount = $result->count();
    $chars = array();
    foreach($characters as $character) {
      if($character->guild == 0)  $guildName = "";
      else $guildName = $this->guildModel->getGuildName($character->guild);
      $chars[] = (object) array(
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      );
    }
    return $chars;
  }
}
?>