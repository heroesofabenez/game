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
  protected $guildMOdel;
  
  function __construct(\Nette\Database\Context $db, \HeroesofAbenez\GuildModel $guildModel) {
    $this->db = $db;
    $this->guildModel = $guildModel;
  }
  
  /**
   * Gets list of all characters
   * @param \Nette\Di\Container $container
   * @param \Nette\Utils\Paginator $paginator
   * @return array List of all characters (id, name, level, guild)
   */
  function characters(\Nette\Di\Container $container, \Nette\Utils\Paginator $paginator) {
    $characters = $this->db->table("characters")->order("level, experience, id")
      ->limit($paginator->getLength(), $paginator->getOffset());
    $result = $this->db->table("characters");
    $paginator->itemCount = $result->count("id");
    $chars = array();
    foreach($characters as $character) {
      if($character->guild == 0)  $guildName = "";
      else $guildName = $this->guildModelgetGuildName($character->guild);
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
    return $this->guildModel->listOfGuilds();
  }
}
?>
