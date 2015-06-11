<?php
namespace HeroesofAbenez\Chat;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
class GlobalChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user) {
    $stage = $database->table("quest_stages")->get($user->identity->stage);
    parent::__construct($database, "chat_global", "area", $stage->area);
  }
  
  /**
   * Gets characters in the current chat
   * 
   * @return array
   */
  function getCharacters() {
    $stages = $this->db->table("quest_stages")
      ->where("area", $this->id);
    $stagesIds = array();
    foreach($stages as $stage) {
      $stagesIds[] = $stage->id;
    }
    $characters = $this->db->table("characters")
      ->where("current_stage", $stagesIds);
    foreach($characters as $char) {
      $this->names[$char->id] = $char->name;
    }
    return $characters;
  }
}
?>