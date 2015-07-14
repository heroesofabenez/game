<?php
namespace HeroesofAbenez\NPC;

/**
 * NPC Quests Control
 *
 * @author Jakub Konečný
 */
class NPCQuestsControl extends \Nette\Application\UI\Control {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $questModel;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Entities\NPC */
  protected $npc;
  
  /**
   * @param \HeroesofAbenez\Model\Quest $questModel
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   */
  function __construct(\HeroesofAbenez\Model\Quest $questModel, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->questModel = $questModel;
    $this->user = $user;
    $this->db = $db;
  }
  
  function setNpc(\HeroesofAbenez\Entities\NPC $npc) {
    $this->npc = $npc;
  }
  
  /**
   * Gets list of available quests from the npc
   * 
   * @param int $npc Npc's id
   * @return array
   */
  function getQuests() {
    $return = $this->questModel->listOfQuests($this->npc->id);
    $playerQuests = $this->db->table("character_quests")
      ->where("character", $this->user->id);
    foreach($return as $key => $quest) {
      foreach($playerQuests as $pquest) {
        if($quest->id == $pquest->quest AND $pquest->progress > 2) {
          unset($return[$key]);
          continue 2;
        } elseif($quest->id == $pquest->quest AND $pquest->progress <= 2) {
          $quest->progress = true;
          continue 2;
        }
      }
      if($quest->needed_level > 0) {
        if($this->user->identity->level < $quest->needed_level) unset($return[$key]);
      } elseif($quest->needed_quest > 0) {
        if(!$this->questModel->isFinished($quest->id)) unset($return[$key]);
      }
    }
    return $return;
  }
  
  /**
   * @return void
   */
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/npcQuests.latte");
    $template->id = $this->npc->id;
    $template->quests = $this->getQuests();
    $template->render();
  }
}
?>