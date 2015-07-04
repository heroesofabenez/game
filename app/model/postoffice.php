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
  
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, \HeroesofAbenez\Model\Profile $profileModel) {
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
   * 
   * @param int $id
   * @return \stdClass|int
   */
  function message($id) {
    $message = $this->db->table("messages")->get($id);
    if(!$message) return 0;
    if(!$this->canShow($message)) return 1;
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
   * @return bool
   */
  function sendMessage(array $data) {
    $result = $this->db->query("INSERT INTO messages", $data);
    return $result;
  }
}
?>