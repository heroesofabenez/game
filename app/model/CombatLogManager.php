<?php
namespace HeroesofAbenez\Model;

/**
 * Combat Log
 *
 * @author Jakub Konečný
 */
class CombatLogManager {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context */
  protected $db;
  
  function __construct(\Nette\Database\Context $db) {
    $this->db = $db;
  }
  
  /**
   * Load specified combat from database
   * 
   * @param int $id Combat's id
   * @return \Nette\Database\Table\ActiveRow|bool
   */
  function read($id) {
    return $this->db->table("combats")->get($id);
  }
  
  /**
   * Log new combat
   * 
   * @param string $text Combat log
   * @return int New record's id
   */
  function write($text) {
    $data = [
      "text" => $text, "when" => time()
    ];
    $this->db->query("INSERT INTO combats", $data);
    $combatId = $this->db->getInsertId("logs");
    return $combatId;
  }
}
?>