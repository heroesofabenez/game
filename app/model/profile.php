<?php
namespace HeroesofAbenez;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
   */
class Profile extends \Nette\Object {
  /** @var \Nette\Database\Context  */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\CharacterModel */
  protected $characterModel;
  /** @var \HeroesofAbenez\Permissions */
  protected $permissionsModel;
  
  /**
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \HeroesofAbenez\CharacterModel $characterModel, \HeroesofAbenez\Permissions $permissionsModel) {
    $this->db = $db;
    $this->characterModel = $characterModel;
    $this->cache = $cache;
    $this->permissionsModel = $permissionsModel;
  }
  
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @return string
   */
  function getRaceName($id) {
    $racesList = $this->characterModel->getRacesList();
    return $racesList[$id];
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @return string
   */
  function getClassName($id) {
    $classesList = $this->characterModel->getClassesList();
    return $classesList[$id];
  }
  
  /**
   * Get name of specified rank
   * 
   * @param int $id
   * @return string
   */
  function getRankName($id) {
    $ranks = $this->permissionsModel->getRoles();
    return $ranks[$id]["name"];
  }
  
  /**
   * @return array
   */
  function getCharacters() {
    $return = array();
    $characters = $this->cache->load("characters");
    if($characters === NULL) {
      $characters = $this->db->table("characters");
      foreach($characters as $char) {
        $return[$char->id] = array(
          "id" => $char->id, "name" => $char->name
        );
      }
      $this->cache->save("characters", $return);
    } else {
      $return = $characters;
    }
    return $return;
  }
  
  /**
   * Get character's id
   * 
   * @param string $name Character's name
   * @return int
   */
  function getCharacterId($name) {
    $characters = $this->getCharacters();
    foreach($characters as $char) {
      if($char["name"] == $name) return $char["id"];
    }
    return 0;
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @return string
   */
  function getCharacterName($id) {
    $characters = $this->getCharacters();
    return $characters[$id]["name"];
  }
  
  /**
   * Get character's guild
   * 
   * @param string $id Character's id
   * @return int
   */
  function getCharacterGuild($id) {
    $char = $this->db->table("characters")->get($id);
    if(!$char) return 0;
    return $char->guild;
  }
  
  /**
   * Gets basic data about specified player
   * @param integer $id character's id
   * @param \Nette\Di\Container $container
   * @return array info about character
   */
  function view($id, \Nette\Di\Container $container) {
    $return = array();
    $char = $this->db->table("characters")->get($id);
    if(!$char) { return false; }
    $stats = array(
      "name", "gender", "level", "race", "description", "strength",
      "dexterity", "constitution", "intelligence", "charisma"
    );
    foreach($stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    
    $return["race"] = $this->getRaceName($char->race);
    $return["occupation"] = $this->getClassName($char->occupation);
    if($char->specialization > 0) {
      $return["specialization"] = "-" . $char->specialization;
    } else {
      $return["specialization"] = "";
    }
    if($char->guild > 0) {
      $guildName = $container->getService("model.guild")->getGuildName($char->guild);
      $guildRank = $this->getRankName($char->guildrank);
      $return["guild"] = "Guild: $guildName<br>Position in guild: " . ucfirst($guildRank);
    } else {
      $return["guild"] = "Not a member of guild";
    }
    $activePet = $this->db->table("pets")->where("owner=$char->id")->where("deployed=1");
    if($activePet->count("*") == 1) {
      $petType = $this->db->table("pet_types")->get($activePet->type);
      if($activePet->name == "pets") $petName = "Unnamed"; else $petName = $activePet->name . ",";
      $bonusStat = strtoupper($petType->bonus_stat);
      $return["active_pet"] = "Active pet: $petName $petType->name, +$petType->bonus_value% $bonusStat";
    } else {
      $return["active_pet"] = "No active pet";
    }
    return $return;
  }
}
?>