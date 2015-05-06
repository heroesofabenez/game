<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Game extends Nette\Object {
  protected $db;
  protected $latte;
  protected $container;
  protected $site_name;
  protected $base_url;
  private function __construct() { }
  static function Init(Nette\Configurator $config) {
    $game = new self;
    $game->container = $config->createContainer();
    $game->latte = $game->container->getService("latte");
    $game->latte->setTempDirectory(APP_DIR . "/temp/cache/Nette.Latte");
    $game->site_name= $game->container->parameters["application"]["siteName"];
    $game->base_url = $game->container->parameters["application"]["baseUrl"];
    $game->db = $game->container->getService("database.default.context");
    $game->db->structure->rebuild();
    return $game;
  }
  
  function &__get($var) {
    if(isset($this->$var)) { return $this->$var; }
  }
  
  function profile($id) {
    $return = array();
    $db = $this->db;
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
  
  function myGuild() {
    return 0;
  }
  
  function guildList() {
    $return = array();
    $db = $this->db;
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
  
  function guildPage($id) {
    $return = array();
    $db = $this->db;
    $guild = $db->table("guilds")->get($id);
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guild_rank DESC, id");
    foreach($members as $member) {
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($member->rank->name));
    }
    return $return;
  }
  
  function page404() {
    header("HTTP/1.1 404 Not Found");
  }
  
  function getAction() {  
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
  
  function run() {
    $action = $this->getAction();
    $parameters = array(
      "site_name" => $this->site_name,
      "base_url" => $this->base_url
    );
    switch($action[0]) {
case "homepage":
  $template = APP_DIR . "/templates/HomePage.latte";
  break;
case "profile":
  $parameters = array_merge($parameters, $this->profile($action[1]));
  $template = APP_DIR . "/templates/Profile.latte";
  break;
case "myguild":
  if($this->myGuild() == 0) $template = APP_DIR . "/templates/GuildNone.latte";
  else $template = APP_DIR . "/templates/Guild.latte";
  break;
case "guild":
  switch($action[1]) {
  case "create":
    $template = APP_DIR . "/templates/GuildCreate.latte";
    break;
  case "join":
    $template = APP_DIR . "/templates/GuildJoin.latte";
    $parameters = array_merge($parameters, $this->guildList());
    break;
  default:
    $parameters = array_merge($parameters, $this->guildPage($action[1]));
    $template = APP_DIR . "/templates/GuildPage.latte";
    break;
  }
  break;
case "notfound":
  $this->page404();
  $template = APP_DIR . "/templates/Page404.latte";
  break;
}
    $this->latte->render($template, $parameters);
  }
}
?>