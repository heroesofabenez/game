<?php
namespace HeroesofAbenez\Chat;

/**
 * Basic Chat Control
 *
 * @author Jakub Konečný
 */
abstract class ChatControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Database\Context Database context */
  protected $db;
  /** @var string*/
  protected $table;
  /** @var string*/
  protected $param;
  /** @var int*/
  protected $id;
  /** @var array */
  protected $names = array();
  
  /**
   * @param \Nette\Database\Context $database
   * @param string $table
   * @param int $id
   */
  function __construct(\Nette\Database\Context $database, $table, $param, $id) {
    $this->db = $database;
    if(is_string($table)) $this->table = $table;
    if(is_string($param)) $this->param = $param;
    if(is_int($id)) $this->id = $id;
  }
  
  /**
   * Gets texts for the current chat
   * 
   * @return array
   */
  function getTexts() {
    $countR = $this->db->query("SELECT count(*) AS amount FROM $this->table WHERE {$this->param}=$this->id");
    foreach($countR as $count) { }
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count->amount);
    $paginator->setItemsPerPage(25);
    $paginator->setPage($paginator->pageCount);
    $data = $this->db->table($this->table)
      ->where($this->param, $this->id)
      ->limit($paginator->length, $paginator->offset);
    $lines = array();
    foreach($data as $line) {
      if(!isset($this->names[$line->character])) {
        $id = $line->character;
        $char = $this->db->table("characters")->get($id);
        $this->names[$id] = $char->name;
      }
      $lines[] = (object) array(
        "id" => $line->id, "character" => $this->names[$line->character], "when" => $line->when, "message" => $line->message
      );
    }
    return $lines;
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return array
   */
  function getCharacters() {
    $characters = $this->db->table("characters")
      ->where($this->param, $this->id);
    foreach($characters as $char) {
      $this->names[$char->id] = $char->name;
    }
    return $characters;
  }
  
  /**
   * Renders the chat
   * 
   * @return void
   */
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/chat.latte");
    $template->characters = $this->getCharacters();
    $template->texts = $this->getTexts();
    $template->render();
  }
  
  /**
   * Submits new message
   * 
   * @param int $uid Character's id
   * @param string $message message
   * @return void
   */
  function newMessage($uid, $message) {
    $data = array(
      "message" => $message,"character" => $uid, "$this->param" => $this->id
      );
    $this->db->query("INSERT INTO $this->table", $data);
  }
}
?>