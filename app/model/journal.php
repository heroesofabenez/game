<?php
namespace HeroesofAbenez;

class JournalQuest extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  
  function __construct($id, $name) {
    $this->id = (int) $id;
    $this->name = $name;
  }
}

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
  
  /**
   * Gets character's inventory
   * 
   * @param \Nette\DI\Container $container
   * @return array
   */
  static function inventory(\Nette\DI\Container $container) {
    $return = array();
    $uid = $container->getService("security.user")->id;
    $db = $container->getService("database.default.context");
    $char = $db->table("characters")->get($uid);
    $return["money"] = $char->money;
    $return["items"] = array();
    $return["equipments"] = array();
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @param \Nette\DI\Container $container
   * @return array
   */
  static function pets(\Nette\DI\Container $container) {
    $return = array();
    $uid = $container->getService("security.user")->id;
    $db = $container->getService("database.default.context");
    $pets = $db->table("pets")
      ->where("owner", $uid);
    foreach($pets as $pet) {
      $type = $db->table("pet_types")->get($pet->id);
      $return[] = array(
        "id" => $pet->id, "name" => $pet->name,
        "deployed" => (bool) $pet->deployed, "type" => $type->name
      );
    }
    return $return;
  }
  
   /**
   * Gets character's quests
   * 
   * @param \Nette\DI\Container $container
   * @return array
   */
  static function quests(\Nette\DI\Container $container) {
    $return = array();
    $uid = $container->getService("security.user")->id;
    $db = $container->getService("database.default.context");
    $quests = $db->table("character_quests")
      ->where("character", $uid);
    foreach($quests as $row) {
      if($row->progress >3) {
        $quest = $db->table("quests")->get($row->id);
        $return[] = new JournalQuest($quest->id, $quest->name);
      }
    }
    return $return;
  }
}
?>