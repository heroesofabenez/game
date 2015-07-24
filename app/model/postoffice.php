<?php
namespace HeroesofAbenez\Model;

/**
 * Post Office Model
 *
 * @author Jakub Konečný
 */
class PostOffice extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var HeroesofAbenez\Model\Profile */
  protected $profileModel;
  
  /**
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Model\Profile $profileModel
   */
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, Profile $profileModel) {
    $this->db = $db;
    $this->user = $user;
    $this->profileModel = $profileModel;
  }
  
  /**
   * Gets list of received messages
   * 
   * @return array
   */
  function inbox() {
    $return = array();
    $messages = $this->db->table("messages")
      ->where("to", $this->user->id);
    foreach($messages as $message) {
      $from = $this->profileModel->getCharacterName($message->from);
      $return[] = (object) array(
        "id" => $message->id, "from" => $from, "subject" => $message->subject,
        "text" => $message->text, "sent" => $message->sent, "read" => $message->read
      );
    }
    return $return;
  }
  
  /**
   * Gets list of received messages
   * 
   * @return array
   */
  function sent() {
    $return = array();
    $messages = $this->db->table("messages")
      ->where("from", $this->user->id);
    foreach($messages as $message) {
      $to = $this->profileModel->getCharacterName($message->to);
      $return[] = (object) array(
        "id" => $message->id, "to" => $to, "subject" => $message->subject,
        "text" => $message->text, "sent" => $message->sent, "read" => $message->read
      );
    }
    return $return;
  }
  
  protected function canShow($message) {
    if($message->from == $this->user->id OR $message->to == $this->user->id) return true;
    else return false;
  }
  
  /**
   * Show specified message
   * 
   * @param int $id
   * @return \stdClass
   * @throws \Nette\Application\BadRequestException
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function message($id) {
    $message = $this->db->table("messages")->get($id);
    if(!$message) throw new \Nette\Application\BadRequestException;
    if(!$this->canShow($message)) throw new \Nette\Application\ForbiddenRequestException;
    $from = $this->profileModel->getCharacterName($message->from);
    $to = $this->profileModel->getCharacterName($message->to);
    $return = (object) array(
      "id" => $message->id, "from" => $from, "to" => $to, "subject" => $message->subject,
        "text" => $message->text, "sent" => $message->sent, "read" => $message->read
    );
    return $return;
  }
  
  /**
   * @param array $data
   * @return void
   */
  function sendMessage(array $data) {
    $this->db->query("INSERT INTO messages", $data);
  }
  
  /**
   * @return array
   */
  function getRecipients() {
    $chars = array();
    $characters = $this->db->table("characters")
      ->order("id");
    foreach($characters as $char) {
      $chars[$char->id] = $char->name;
    }
    return $chars;
  }
}
?>