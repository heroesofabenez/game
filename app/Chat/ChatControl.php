<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Basic Chat Control
 *
 * @author Jakub Konečný
 */
abstract class ChatControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Database\Context Database context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ChatCommandsProcessor */
  protected $processor;
  /** @var string*/
  protected $table;
  /** @var string*/
  protected $param;
  /** @var string */
  protected $param2;
  /** @var int*/
  protected $id;
  /** @var int */
  protected $id2;
  /** @var array */
  protected $names = [];
  
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, ChatCommandsProcessor  $processor, string $param, int $id, string $param2 = NULL, $id2 = NULL) {
    parent::__construct();
    $this->db = $db;
    $this->user = $user;
    $this->processor = $processor;
    $this->param = $param;
    $this->param2 = $param2 ?? $param;
    $this->id = $id;
    $this->id2 = $id2 ?? $id;
  }
  
  /**
   * Gets texts for the current chat
   * 
   * @return \stdClass[]
   */
  function getTexts(): array {
    $count = $this->db->table("chat_messages")->where($this->param, $this->id)->count("*");
    $paginator = new \Nette\Utils\Paginator;
    $paginator->setItemCount($count);
    $paginator->setItemsPerPage(25);
    $paginator->setPage($paginator->pageCount);
    $data = $this->db->table("chat_messages")
      ->where($this->param, $this->id)
      ->limit($paginator->length, $paginator->offset);
    $lines = [];
    foreach($data as $line) {
      if(!isset($this->names[$line->character])) {
        $id = $line->character;
        $char = $this->db->table("characters")->get($id);
        $this->names[$id] = $char->name;
      }
      $lines[] = (object) [
        "id" => $line->id, "character" => $this->names[$line->character], "when" => $line->when, "message" => $line->message
      ];
    }
    return $lines;
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return \Nette\Database\Table\Selection
   */
  function getCharacters(): \Nette\Database\Table\Selection {
    $characters = $this->db->table("characters")
      ->where($this->param2, $this->id2);
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
  function render(): void {
    $this->template->setFile(__DIR__ . "/chat.latte");
    $this->template->characters = $this->getCharacters();
    $this->template->texts = $this->getTexts();
    $this->template->render();
  }
  
  /**
   * Submits new message
   * 
   * @param string $message message
   * @return void
   */
  function newMessage(string $message): void {
    $result = $this->processor->parse($message);
    if($result) {
      $this->presenter->flashMessage($result);
    } else {
      $data = [
        "message" => $message,"character" => $this->user->id, "$this->param" => $this->id
      ];
      $this->db->query("INSERT INTO chat_messages", $data);
    }
    $this->presenter->redirect("this");
  }
}
?>