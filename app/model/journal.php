<?php
namespace HeroesofAbenez;

class JournalQuest extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  
  /**
   * @param int $id
   * @param string $name
   */
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
class Journal extends \Nette\Object {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \HeroesofAbenez\QuestModel */
  protected $questModel;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Security\User $user,\Nette\Database\Context $db) {
    $this->user = $user;
    $this->db = $db;
  }
  
  function setQuestModel(\HeroesofAbenez\QuestModel $questModel) {
    $this->questModel = $questModel;
  }
  
  /**
   * Gets basic info for character's journal
   * 
   * @param \Nette\DI\Container $container
   * @return array
   */
  function basic(\Nette\DI\Container $container) {
    $user = $this->user->identity;
    $locationModel = $container->getService("model.location");
    $character = $this->db->table("characters")->get($user->id);
    $stages = $locationModel->listOfStages();
    $stage = $stages[$user->stage];
    $return = array(
      "name" => $user->name, "gender" => $user->gender, "race" => $user->race,
      "occupation" => $user->occupation, "specialization" => $user->specialization,
      "level" => $user->level, "whiteKarma" => $user->white_karma,
      "neutralKarma" => $user->neutral_karma, "darkKarma" => $user->dark_karma,
      "experiences" => $character->experience, "description" => $character->description,
      "stageName" => $stage->name, "areaName" => $locationModel->getAreaName($stage->area)
    );
    if($user->guild > 0) {
      $guildModel = $container->getService("model.guild");
      $return["guild"] = $guildModel->getGuildName($user->guild);
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
  function inventory() {
    $return = array();
    $uid = $this->user->id;
    $char = $this->db->table("characters")->get($uid);
    $return["money"] = $char->money;
    $return["items"] = array();
    $return["equipments"] = array();
    return $return;
  }
  
  /**
   * Gets character's pets
   * 
   * @return array
   */
  function pets() {
    $return = array();
    $uid = $this->user->id;
    $pets = $this->db->table("pets")
      ->where("owner", $uid);
    foreach($pets as $pet) {
      $type = $this->db->table("pet_types")->get($pet->id);
      $return[] = new Pet($pet->id, $type->name, $pet->name, $pet->bonus_stat, $pet->bonus_value, $pet->deployed);
    }
    return $return;
  }
  
   /**
   * Gets character's quests
   * 
   * @return array
   */
  function quests() {
    $return = array();
    $uid = $this->user->id;
    $quests = $this->db->table("character_quests")
      ->where("character", $uid);
    foreach($quests as $row) {
      if($row->progress < 3) {
        $quest = $this->questModel->view($row->id);
        $return[] = new JournalQuest($quest->id, $quest->name);
      }
    }
    return $return;
  }
}
?>