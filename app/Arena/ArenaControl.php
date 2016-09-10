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
    HeroesofAbenez\Model\CombatDuel,
    HeroesofAbenez\Model\OpponentNotFoundException;

/**
 * Basic Arena Control
 *
 * @author Jakub Konečný
 */
abstract class ArenaControl extends \Nette\Application\UI\Control {
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\CombatHelper */
  protected $combatHelper;
  /** @var \HeroesofAbenez\Model\CombatDuel */
  protected $combat;
  /** @var \HeroesofAbenez\Model\CombatLogManager */
  protected $log;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  /** @var string */
  protected $arena;
  
  function __construct(\Nette\Security\User $user, \HeroesofAbenez\Model\CombatHelper $combatHelper, \HeroesofAbenez\Model\CombatDuel $combat, \HeroesofAbenez\Model\CombatLogManager $log, \Nette\Database\Context $db, \Kdyby\Translation\Translator $translator) {
    $this->user = $user;
    $this->combatHelper = $combatHelper;
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
    try {
      $player = $this->combatHelper->getPlayer($id);
    } catch(OpponentNotFoundException $e) {
      throw $e;
    }
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
    $this->combat->setParticipants($player, $opponent);
    $winner = $this->combat->execute();
    if($winner === 1) {
      $rewards = $this->calculateRewards($player, $opponent);
      $data = "money=money+{$rewards["money"]}, experience=experience+{$rewards["experience"]}";
      $where = ["id" => $this->user->id];
      $this->db->query("UPDATE characters SET $data WHERE ?", $where);
      $this->combat->log->logText("$player->name gets {$rewards["money"]} silver marks and {$rewards["experience"]} experiences.");
    }
    $combatId = $this->saveCombat($this->combat->log);
    $this->presenter->redirect("Combat:view", ["id" => $combatId]);
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
?>