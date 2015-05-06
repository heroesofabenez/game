<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Game extends Nette\Object {
  static protected $db;
  static protected $latte;
  static protected $container;
  private function __construct() { }
  
  static function getDb() {
    return self::$db;
  }
  
  static function getLatte() {
    return self::$latte;
  }
  
  static function getContainer() {
    return self::$container;
  }
  
  static function profile($id) {
    $return = array();
    $db = self::$db;
    $char = $db->table("characters")->get($id);
    
    $return["name"] = $char->name;
    $return["gender"] = $char->gender;
    $return["level"] = $char->level;
    $return["race"] = $char->race;
    $return["description"] = $char->description;
    $return["strength"] = $char->strength;
    $return["dexterity"] = $char->dexterity;
    $return["constitution"] = $char->constitution;
    $return["intelligence"] = $char->intelligence;
    $return["charisma"] = $char->charisma;
    $return["description"] = $char->description;
    
    $race = $db->table("character_races")->get($char->race);
    $return["race"] = $race->name;
    $occupation = $db->table("character_classess")->get($char->occupation);
    $return["occupation"] = $occupation->name;
    if($char->specialization > 0) {
      $return["specialization"] = "-" . $char->specialization;
    } else {
      $return["specialization"] = "";
    }
    if($char->guild > 0) {
      $guild = $db->table("guilds")->get($char->guild);
      $guildRank = $db->table("guild_ranks")->get($char->guild_rank);
      $return["guild"] = "Guild: $guild->name<br>Position in guild: " . ucfirst($guildRank->name);
    } else {
      $return["guild"] = "Not a member of guild";
    }
    $activePet = $db->table("pets")->where("owner=$char->id")->where("deployed=1");
    if($activePet->count("*") == 1) {
      $petType = $db->table("pet_types")->get($activePet->type);
      if($activePet->name == "pets") $petName = "Unnamed"; else $petName = $activePet->name . ",";
      $bonusStat = strtoupper($petType->bonus_stat);
      $return["active_pet"] = "Active pet: $petName $petType->name, +$petType->bonus_value% $bonusStat";
    } else {
      $return["active_pet"] = "No active pet";
    }
    return $return;
  }
  
  static function myGuild() {
    return 0;
  }
  
  static function guildList() {
    $return = array();
    $db = self::$db;
    $guilds = $db->table("guilds");
    foreach($guilds as $guild) {
      if($guild->id == 0) continue;
      $members = $db->table("characters")->where("guild", $guild->id);
      foreach($members as $member) {
        if($member->rank->name == "grandmaster") {
          $leader = $member->name;
          break;
        }
      }
      $return["guilds"][] = array("id" => $guild->id, "name" => $guild->name, "description" => $guild->description, "leader" => $leader, "members" => $members->count("*"));
    }
    return $return;
  }
  
  static function guildPage($id) {
    $return = array();
    $db = self::$db;
    $guild = $db->table("guilds")->get($id);
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guild_rank DESC, id");
    foreach($members as $member) {
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($member->rank->name));
    }
    return $return;
  }
  
  static function page404() {
    header("HTTP/1.1 404 Not Found");
  }
  
  static function getAction() {  
    if(isset($_GET["q"])) { $query = $_GET["q"]; } else { $query = "";  }
    $action = array("homepage", "");
    if(empty($query)) {
      $action[0] = "homepage";
    } elseif($query == "guild") {
      $action[0] = "myguild";
    } else {
      $action[0] = "notfound";
    }
    $pos = strpos($query, "/");
    $q = substr($query, 0, $pos);
    $params = substr($query, $pos+1);
    $withParams = array("profile", "guild");
    if($q !== $query) {
      if(in_array($q, $withParams)) {
        $action[0] = $q;
        $action[1] = $params;
      }
    }
    return $action;
  }
  
  static function run(Nette\Configurator $config) {
    self::$container = $config->createContainer();
    self::$latte = self::$container->getService("latte");
    self::$latte->setTempDirectory(APP_DIR . "/temp/cache/Nette.Latte");
    self::$db = self::$container->getService("database.default.context");
    self::$db->structure->rebuild();
    $action = self::getAction();
    $parameters = array(
      "site_name" => self::$container->parameters["application"]["siteName"],
      "base_url" => self::$container->parameters["application"]["baseUrl"]
    );
    switch($action[0]) {
case "homepage":
  $template = TEMPLATES_DIR . "/HomePage.latte";
  break;
case "profile":
  $parameters = array_merge($parameters, self::profile($action[1]));
  $template = TEMPLATES_DIR . "/Profile.latte";
  break;
case "myguild":
  if(self::myGuild() == 0) $template = APP_DIR . "/templates/GuildNone.latte";
  else $template = TEMPLATES_DIR . "/Guild.latte";
  break;
case "guild":
  switch($action[1]) {
  case "create":
    $template = TEMPLATES_DIR . "/GuildCreate.latte";
    break;
  case "join":
    $template = TEMPLATES_DIR . "/GuildJoin.latte";
    $parameters = array_merge($parameters, self::guildList());
    break;
  default:
    $parameters = array_merge($parameters, self::guildPage($action[1]));
    $template = TEMPLATES_DIR . "/GuildPage.latte";
    break;
  }
  break;
case "notfound":
  self::page404();
  $template = TEMPLATES_DIR . "/Page404.latte";
  break;
}
    self::$latte->render($template, $parameters);
  }
}
?>