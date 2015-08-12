<?php
namespace HeroesofAbenez\Postoffice;

/**
 * Postoffice Control
 *
 * @author Jakub Konečný
 */
class PostofficeControl extends \Nette\Application\UI\Control {
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
  protected function getReceivedMessages() {
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
   * @return void
   */
  function renderInbox() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/postofficeInbox.latte");
    $template->messages = $this->getReceivedMessages();
    $template->render();
  }
  
  /**
   * Gets list of sent messages
   * 
   * @return array
   */
  protected function getSentMessages() {
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
  
  /**
   * @return void
   */
  function renderOutbox() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/postofficeOutbox.latte");
    $template->messages = $this->getSentMessages();
    $template->render();
  }
  
  protected function canShow($message) {
    if($message->from == $this->user->id OR $message->to == $this->user->id) return true;
    else return false;
  }
  
  /**
   * @param int $id
   * @return int
   */
  function messageStatus($id) {
    $message = $this->db->table("messages")->get($id);
    if(!$message) return 0;
    if(!$this->canShow($message)) return -1;
    return 1;
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
   * @param int $id Message's id
   * @return void
   */
  function renderMessage($id) {
    $template = $this->template;
    $template->setFile(__DIR__ . "/postofficeMessage.latte");
    try {
      $message = $this->message($id);
      foreach($message as $key => $value) {
       $template->$key = $value;
      }
    } catch(\Nette\Application\ForbiddenRequestException $e) {
      $this->presenter->forward("cannotshow");
    } catch(\Nette\Application\BadRequestException $e) {
      $this->presenter->forward("notfound");
    }
    $template->render();
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
  
  /**
   * @param array $data
   * @return void
   */
  function sendMessage(array $data) {
    $this->db->query("INSERT INTO messages", $data);
  }
}

interface PostofficeControlFactory {
  /** @return PostofficeControl */
  function create();
}
?>