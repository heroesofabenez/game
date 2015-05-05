<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Game extends Nette\Object {
  protected $db;
  protected $page;
  protected $user;
  protected $config;
  protected $site_name;
  protected $base_url;
  private function __construct() { }
  static function Init(Nette\Configurator $config) {
    $game = new self;
    $game->config = &$config;
    $container = $config->createContainer();
    $game->page = $container->getService("page");
    $game->page->addMeta("content-type", "text/html; charset=utf-8");
    //$game->page->attachStyle("$base_url/style.css");
    //$game->page->attachScript("http://code.jquery.com/jquery-latest.pack.js");
    $game->site_name= $container->parameters["application"]["siteName"];
    $game->base_url = $container->parameters["application"]["baseUrl"];
    $game->db = $container->getService("database.default.context");
    $game->db->structure->rebuild();
    return $game;
  }
  
  function &__get($var) {
    if(isset($this->$var)) { return $this->$var; }
  }
  
  function navigation() {
    $navigation = $this->page->addSection("navigation", "nav");
    $navigation->addLink("Home", "$this->base_url/");
  }
  
  function homePage() {
    $this->page->setTitle("$this->site_name - Home");
    $this->navigation();
  }
  
  function profile($id) {
    $this->page->setTitle("$this->site_name - Profile");
    $this->navigation();
    $db = $this->db;
    $char = $db->table("characters")->get($id);
    $race = $db->table("character_races")->get($char->race);
    $occupation = $db->table("character_classess")->get($char->occupation);
    if($char->specialization > 0) {
      $specialization = "-" . $db->table("character_specialization")->get($char->specialization);
    } else {
      $specialization = "";
    }
    $profileDiv = $this->page->addDiv("profile");
    $profileDiv->addHeading(1, $char->name);
    $profileDiv->addParagraph("$char->gender, level $char->level $race->name $occupation->name$specialization");
    $profileDiv->addParagraph("Base stats:<br>Strength $char->strength, Dexterity $char->dexterity, Constitution $char->constitution, Intelligence $char->intelligence, Charisma $char->charisma");
    if($char->guild > 0) {
      $guild = $db->table("guilds")->get($char->guild);
      $guildRank = $db->table("guild_ranks")->get($char->guild_rank);
      $profileDiv->addParagraph("Guild: $guild->name<br>Position in guild: " . ucfirst($guildRank->name));
    } else {
      $profileDiv->addParagraph("Not a member of guild");
    }
    $profileDiv->addParagraph("More info: $char->description");
  }
  
  function myGuild() {
    $this->navigation();
    $this->page->setTitle("$this->site_name - Guild");
  }
  
  function guildPage($id) {
    $this->navigation();
    $db = $this->db;
    $guild = $db->table("guilds")->get($id);
    $this->page->setTitle("$this->site_name - Guild $guild->name");
    $profileDiv = $this->page->addSection("profile", "article");
    $profileDiv->addHeading(1, $guild->name);
    $profileDiv->addParagraph("Description: $guild->description");
    $profileDiv->addHeading(2, "Members");
    $members = $db->table("characters")->where("guild", $guild->id)->order("guild_rank DESC, id");
    foreach($members as $member) {
      $rank = ucfirst($member->rank->name);
      $profileDiv->inject("$rank: $member->name<br>");
    }
  }
  
  function page404() {
    header("HTTP/1.1 404 Not Found");
    $this->page->setTitle("Not found");
  }
  
  function getAction() {  
    if(isset($_GET["q"])) { $query = $_GET["q"]; } else { $query = "";  }
    $action = array("homepage", "");
    if(empty($query)) {
      $action[0] = "homepage";
    } elseif($query == "profile") {
      $action[0] = "profile";
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
    switch($action[0]) {
case "homepage":
  $this->homePage();
  break;
case "profile":
  $this->profile($action[1]);
  break;
case "myguild":
  $this->myGuild();
  break;
case "guild":
  $this->guildPage($action[1]);
  break;
case "notfound":
  $this->page404();
  break;
}
    echo $this->page->render();
  }
}
?>