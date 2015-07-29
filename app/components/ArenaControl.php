<?php
namespace HeroesofAbenez\Arena;

use Nette\Security\User,
    Nette\Database\Context as Database,
    HeroesofAbenez\Model\Profile,
    HeroesofAbenez\Model\Equipment,
    HeroesofAbenez\Model\CombatLog,
    HeroesofAbenez\Model\CombatLogger,
    Kdyby\Translation\Translator,
    HeroesofAbenez\Entities\Character;

/**
 * Basic Arena Control
 *
 * @author Jakub Konečný
 */
abstract class ArenaControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Equipment */
  protected $equipmentModel;
  /** @var \HeroesofAbenez\Model\CombatLog */
  protected $log;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  /** @var string */
  protected $file;
  
  function __construct(User $user, Profile $profileModel, Equipment $equipmentModel, CombatLog $log, Database $db, Translator $translator) {
    $this->user = $user;
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->log = $log;
    $this->db = $db;
    $this->translator = $translator;
  }
  
  /**
   * Get data for specified player
   * 
   * @param int $id Player's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  protected function getPlayer($id) {
    $data = $this->profileModel->view($id);
    if(!$data) throw new OpponentNotFoundException;
    $pets = $equipment = array();
    if($data["pet"]) $pets[] = $data["pet"];
    unset($data["pet"]);
    $equipmentRows = $this->db->table("character_equipment")
      ->where("character", $id)
      ->where("worn", 1);
    foreach($equipmentRows as $row) {
      $item = $this->equipmentModel->view($row->item);
      $item->worn = true;
      $equipment[] = $item;
    }
    $player = new Character($data, $equipment, $pets);
    return $player;
  }
  
  /**
   * @return array
   */
  abstract protected function getOpponents();
  
  /**
   * @return void
   */
  function render() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/$this->file.latte");
    $template->opponents = $this->getOpponents();
    $template->render();
  }
  
  /**
   * @param int $id
   * @return void
   */
  abstract function handleFight($id);
  
  /**
   * Save log from combat
   * 
   * @param CombatLogger $logger
   * @return int Combat's id
   */
  function saveCombat(CombatLogger $logger) {
    $log = (string) $logger;
    return $this->log->write($log);
  }
}

class OpponentNotFoundException extends \Exception {
  
}
?>