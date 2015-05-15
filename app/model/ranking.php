<?php
namespace HeroesofAbenez;

  /**
   * Model Ranking
   * 
   * @author Jakub Konečný
   */
class Ranking extends \Nette\Object {
  /**
   * Gets list of all characters
   * @param \Nette\Di\Container $container
   * @param \Nette\Utils\Paginator $paginator
   * @return array List of all characters (id, name, level, guild)
   */
  static function characters(\Nette\Di\Container $container, \Nette\Utils\Paginator $paginator) {
    $db = $container->getService("database.default.context");
    $characters = $db->table("characters")->order("level, experience, id")
      ->limit($paginator->getLength(), $paginator->getOffset());
    $result = $db->table("characters");
    $paginator->itemCount = $result->count("id");
    $chars = array();
    $guilds = GuildModel::listOfGuilds($container);
    foreach($characters as $character) {
      if($character->guild == 0)  $guildName = "";
      else $guildName = GuildModel::getGuildName($character->guild, $container);
      $chars[] = array(
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      );
    }
    return $chars;
  }
  
  /**
   * Gets list of all guilds
   * @param \Nette\Di\Container $container
   * @return array List of all guilds (name, number of members)
   */
  static function guilds(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $guilds = $cache->load("guilds");
    $return = array();
    if($guilds === NULL) {
      $db = $container->getService("database.default.context");
      $guilds = $db->table("guilds");
      foreach($guilds as $guild) {
        if($guild->id == 0) continue;
        $members = $db->table("characters");
        $count = 0;
        $leader = "";
        foreach($members as $member) {
          if($member->guild == $guild->id) $count++;
          if($member->guildrank == 7) $leader = $member->name;
        }
        $return[$guild->id] = new Guild($guild->id, $guild->name, $guild->description, $count, $leader);
      }
      $cache->save("guilds", $return);
    } else {
      $return = $guilds;
    }
    return $return;
  }
}
?>
