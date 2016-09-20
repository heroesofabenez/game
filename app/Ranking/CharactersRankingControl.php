<?php
namespace HeroesofAbenez\Ranking;

/**
 * Characters Ranking Control
 * 
 * @author Jakub Konečný
 */
class CharactersRankingControl extends RankingControl {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\Model\Guild */
  protected $guildModel;
  
  /**
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Model\Guild $guildModel
   */
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\Model\Guild $guildModel) {
    $this->db = $db;
    $this->guildModel = $guildModel;
    parent::__construct("Characters", ["name", "level", "guild"], "Profile", "Profile");
  }
  
  /**
   * @return array
   */
  function getData(): array {
    $this->paginator->itemCount = $this->db->table("characters")->count("*");
    $characters = $this->db->table("characters")->order("level DESC, experience DESC, id")
      ->limit($this->paginator->getLength(), $this->paginator->getOffset());
    $chars = [];
    foreach($characters as $character) {
      if($character->guild == 0)  $guildName = "";
      else $guildName = $this->guildModel->getGuildName($character->guild);
      $chars[] = (object) [
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      ];
    }
    return $chars;
  }
}

interface CharactersRankingControlFactory {
  /** @return \HeroesofAbenez\Ranking\CharactersRankingControl */
  function create();
}
?>