<?php
namespace HeroesofAbenez\Ranking;

class CharactersRankingControl extends RankingControl {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Guild */
  protected $guildModel;
  
  /**
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Guild $guildModel
   */
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\Guild $guildModel) {
    $this->db = $db;
    $this->guildModel = $guildModel;
    parent::__construct("Characters", array("name", "level", "guild"), "Profile", "Profile");
  }
  
  /**
   * @return array
   */
  function getData() {
    $this->paginator->itemCount = $this->db->table("characters")->count("*");
    $characters = $this->db->table("characters")->order("level, experience, id")
      ->limit($this->paginator->getLength(), $this->paginator->getOffset());
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