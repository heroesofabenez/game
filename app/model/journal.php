<?php
namespace HeroesofAbenez;

/**
 * Journal Model
 *
 * @author Jakub Konečný
 */
class Journal {
  /**
   * Gets basic info for character's journal
   * 
   * @param \Nette\DI\Container $container
   * @return array
   */
  static function basic(\Nette\DI\Container $container) {
    $return = array();
    $user = $container->getService("security.user");
    $return["name"] = $user->identity->name;
    $return["gender"] = $user->identity->gender;
    $return["race"] = $user->identity->race;
    $return["occupation"] = $user->identity->occupation;
    $return["specialization"] = $user->identity->specialization;
    $return["level"] = $user->identity->level;
    $return["whiteKarma"] = $user->identity->white_karma;
    $return["neutralKarma"] = $user->identity->neutral_karma;
    $return["darkKarma"] = $user->identity->dark_karma;
    $db = $container->getService("database.default.context");
    $character = $db->table("characters")->get($user->id);
    $return["experiences"] = $character->experience;
    $return["description"] = $character->description;
    if($user->identity->guild > 0) {
      $return["guild"] = GuildModel::getGuildName($user->identity->guild, $container);
      $return["guildRank"] = ucfirst($user->identity->roles[0]);
    } else {
      $return["guild"] = false;
    }
    $stages = Location::listOfStages($container);
    $stage = $stages[$user->identity->stage];
    $return["stageName"] = $stage->name;
    $return["areaName"] = Location::getAreaName($stage->area, $container);
    return $return;
  }
}
?>