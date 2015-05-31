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
    $user = $container->getService("security.user")->identity;
    $db = $container->getService("database.default.context");
    $character = $db->table("characters")->get($user->id);
    $stages = Location::listOfStages($container);
    $stage = $stages[$user->stage];
    $return = array(
      "name" => $user->name, "gender" => $user->gender, "race" => $user->race,
      "occupation" => $user->occupation, "specialization" => $user->specialization,
      "level" => $user->level, "whiteKarma" => $user->white_karma,
      "neutralKarma" => $user->neutral_karma, "darkKarma" => $user->dark_karma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $stage->name, "areaName" => Location::getAreaName($stage->area, $container)
    );
    if($user->guild > 0) {
      $return["guild"] = GuildModel::getGuildName($user->guild, $container);
      $return["guildRank"] = ucfirst($user->roles[0]);
    } else {
      $return["guild"] = false;
    }
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