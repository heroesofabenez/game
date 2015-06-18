<?php
namespace HeroesofAbenez\Chat;


/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
class LocalChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user) {
    $stage = $user->identity->stage;
    parent::__construct($database, "chat_local", "stage", $stage);
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return array
   */
  function getCharacters() {
    $characters = $this->db->table("characters")
      ->where("current_stage", $this->id);
    foreach($characters as $char) {
      $this->names[$char->id] = $char->name;
    }
    return $characters;
  }
}
?>