<?php
namespace HeroesofAbenez;

  /**
   * Model Ranking
   * 
   * @author Jakub Konečný
   */
class Ranking extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\GuildModel */
  protected $guildModel;
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\GuildModel $guildModel) {
    $this->db = $db;
    $this->guildModel = $guildModel;
  }
  
  /**
   * Gets list of all characters
   * @param \Nette\Utils\Paginator $paginator
   * @return array List of all characters (id, name, level, guild)
   */
  function characters(\Nette\Utils\Paginator $paginator) {
    $characters = $this->db->table("characters")->order("level, experience, id")
      ->limit($paginator->getLength(), $paginator->getOffset());
    $result = $this->db->table("characters");
    $paginator->itemCount = $result->count();
    $chars = array();
    foreach($characters as $character) {
      if($character->guild == 0)  $guildName = "";
      else $guildName = $this->guildModel->getGuildName($character->guild);
      $chars[] = array(
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      );
    }
    return $chars;
  }
  
  /**
   * Gets list of all guilds
   * @return array List of all guilds (name, number of members)
   */
  function guilds() {
    $return = array();
    $result = $this->guildModel->listOfGuilds();
    foreach($result as $row) {
      $data[] = (array) $row;
    }
    $data2 = Utils\Arrays::orderby($data, "members", SORT_DESC, "id", SORT_ASC);
    foreach($data2 as $row2) {
      $return[] = new Guild($row2["id"], $row2["name"], "", $row2["members"]);
    }
    return $return;
  }
}
?>
