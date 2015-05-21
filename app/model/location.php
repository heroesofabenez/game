<?php
namespace HeroesofAbenez;

/**
 * Data structure for stage
 * 
 * @author Jakub Konečný
 */
class Stage extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int minimum level to enter stage */
  public $required_level;
  /** @var int id of race needed to enter stage */
  public $required_race;
  /** @var int id of class needed to enter stage */
  public $required_occupation;
  /** @var int id of parent area */
  public $area;
  /** @var int order in area */
  public $order;
  
  function __construct($id, $name, $description, $required_level, $required_race, $required_occupation, $area, $order) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->required_level = $required_level;
    $this->required_race = $required_race;
    $this->required_occupation = $required_occupation;
    $this->area = $area;
    $this->order = $order;
  }
}

/**
 * Data structure for area
 * 
 * @author Jakub Konečný
 */
class Area extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int minimum level to enter stage */
  public $required_level;
  /** @var int id of race needed to enter stage */
  public $required_race;
  /** @var int id of class needed to enter stage */
  public $required_occupation;
  
  function __construct($id, $name, $description, $required_level, $required_race, $required_occupation) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->required_level = $required_level;
    $this->required_race = $required_race;
    $this->required_occupation = $required_occupation;
  }
}

class NPC extends \Nette\Object {
  public $id;
  public $name;
  public $race;
  public $sprite;
  public $portrait;
  public $stage;
  public $pos_x;
  public $pos_y;
  
  function __construct($id, $name, $race, $sprite, $portrait, $stage, $pos_x, $pos_y) {
    $this->id = $id;
    $this->name = $name;
    $this->race = $race;
    $this->sprite = $sprite;
    $this->portrait = $portrait;
    $this->stage = $stage;
    $this->pos_x = $pos_x;
    $this->pos_y = $pos_y;
  }
}

/**
 * Location Model
 * 
 * @author Jakub Konečný
 */
class Location {
  /**
   * Gets list of stages
   * 
   * @param \Nette\Di\Container $container
   * @return array list of stages
   */
  static function listOfStages(\Nette\Di\Container $container) {
    $return = array();
    $cache = $container->getService("caches.locations");
    $stages = $cache->load("stages");
    if($stages === NULL) {
      $db = $container->getService("database.default.context");
      $stages = $db->table("quest_stages");
      foreach($stages as $stage) {
        $return[$stage->id] = new Stage($stage->id, $stage->name, $stage->description, $stage->required_level, $stage->required_race, $stage->required_occupation, $stage->area, $stage->order);
      }
      $cache->save("stages", $return);
    } else {
      $return = $stages;
    }
    return $return;
  }
  
  /**
   * Gets list of areas
   * 
   * @param \Nette\Di\Container $container
   * @return array list of stages
   */
  static function listOfAreas(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.locations");
    $areas = $cache->load("areas");
    if($areas === NULL) {
      $db = $container->getService("database.default.context");
      $areas = $db->table("quest_areas");
      foreach($areas as $area) {
        $return[$area->id] = new Area($area->id, $area->name, $area->description, $area->required_level, $area->required_race, $area->required_occupation);
      }
      $cache->save("areas", $return);
    } else {
      $return = $areas;
    }
    return $return;
  }
  
  /**
   * Gets list of npcs
   * 
   * @param \Nette\Di\Container $container
   * @param int $stage Return npcs only from certain stage, 0 = all stages
   * @return array
   */
  static function listOfNpcs(\Nette\Di\Container $container, $stage = 0) {
    $return = array();
    $cache = $container->getService("caches.locations");
    $npcs = $cache->load("npcs");
    if($npcs === NULL) {
      $db = $container->getService("database.default.context");
      $npcs = $db->table("npcs");
      foreach($npcs as $npc) {
        $return[$npc->id] = new NPC($npc->id, $npc->name, $npc->race, $npc->sprite, $npc->portrait, $npc->stage, $npc->pos_x, $npc->pos_y);
      }
      $cache->save("npcs", $return);
    } else {
      $return = $npcs;
    }
    if($stage > 0) {
      foreach($return as $npc) {
        if($npc->stage !== $stage) unset($return[$npc->id]);
      }
    }
    return $return;
  }
  
  /**
   * Get name of specified stage
   * 
   * @param int $id Id of stage
   * @param \Nette\Di\Container $container
   */
  static function getStageName($id, \Nette\Di\Container $container) {
    $stages = Location::listOfStages($container);
    return $stages[$id]->name;
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   * @param \Nette\Di\Container $container
   */
  static function getAreaName($id, \Nette\Di\Container $container) {
    $areas = Location::listOfAreas($container);
    return $areas[$id]->name;
  }
  
  /**
   * Get data for homepage of location
   * 
   * @param int $location Id of stage
   * @param \Nette\Di\Container $container
   * @return array Data about location
   */
  static function Home($location, \Nette\Di\Container $container) {
    return Location::getStageName($location, $container);
  }
}
?>