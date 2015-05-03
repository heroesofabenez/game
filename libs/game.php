<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Game extends Nette\Object {
  protected $conn;
  protected $page;
  protected $user;
  protected $config;
  private function __construct() { }
  static function Init(Nette\Database\Connection $conn, Page $page, GUser $user, $config = array()) {
    $game = new self;
    $game->conn = &$conn;
    $game->page = &$page;
    $game->user = &$user;
    $game->config = &$config;
    return $game;
  }
  
  function &__get($var) {
    if(isset($this->$var)) { return $this->$var; }
  }
  
  function top() {
    global $base_url;
    $homeDiv = $this->page->addDiv();
    $homeDiv->id = "top";
    $homeLink = new Link("Home", "$base_url");
    $homeLink->id = "home";
    $homeDiv->append($homeLink);
  }
  
  function homePage() {
    $this->page->setTitle("{$this->config["site"]["siteName"]} - Home");
    $this->top();
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
    } else {
      $action[0] = "notfound";
    }
    $pos = strpos($query, "/");
    $q = substr($query, 0, $pos);
    $params = substr($query, $pos+1);
    $withParams = array("profile");
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
case "notfound":
  $this->page404();
  break;
}
    echo $this->page->render();
  }
}
?>