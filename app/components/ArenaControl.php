<?php
namespace HeroesofAbenez\Arena;

use Nette\Security\User,
    Nette\Database\Context as Database,
    HeroesofAbenez\Model\Profile,
    HeroesofAbenez\Model\Equipment,
    HeroesofAbenez\Model\CombatLogManager,
    HeroesofAbenez\Model\CombatLogger,
    Kdyby\Translation\Translator,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Model\CombatBase;

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
  /** @var \HeroesofAbenez\Model\CombatBase */
  protected $combat;
  /** @var \HeroesofAbenez\Model\CombatLogManager */
  protected $log;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  /** @var string */
  protected $arena;
  
  function __construct(User $user, Profile $profileModel, Equipment $equipmentModel, CombatBase $combat, CombatLogManager $log, Database $db, Translator $translator) {
    $this->user = $user;
    $this->profileModel = $profileModel;
    $this->equipmentModel = $equipmentModel;
    $this->combat = $combat;
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
    $template->setFile(__DIR__ . "/arena.latte");
    $template->opponents = $this->getOpponents();
    $template->arena = $this->arena;
    $template->render();
  }
  
  /**
   * @return array
   */
  abstract protected function calculateRewards($player, $opponent);
  
  /**
   * Execute the duel
   * 
   * @param Character $opponent Opponent
   * @return void
   */
  protected function doDuel(Character $opponent) {
    $player = $this->getPlayer($this->user->id);
    $team1 = new Team($player->name);
    $team1->addMember($player);
    $team2 = new Team($opponent->name);
    $team2->addMember($opponent);
    $combat = $this->combat;
    $combat->setTeams($team1, $team2);
    $winner = $combat->execute();
    if($winner === 1) {
      $rewards = $this->calculateRewards($player, $opponent);
      $data = "money=money+{$rewards["money"]}, experience=experience+{$rewards["experience"]}";
      $where = array("id" => $this->user->id);
      $this->db->query("UPDATE characters SET $data WHERE ?", $where);
      $combat->log->logText("$player->name gets {$rewards["money"]} silver marks and {$rewards["experience"]} experiences.");
    }
    $combatId = $this->saveCombat($combat->log);
    $this->presenter->redirect("Combat:view", array("id" => $combatId));
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