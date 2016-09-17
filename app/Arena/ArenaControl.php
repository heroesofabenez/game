<?php
namespace HeroesofAbenez\Arena;

use Nette\Security\User,
    Nette\Database\Context,
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
  /** @var User */
  protected $user;
  /** @var \HeroesofAbenez\Model\CombatHelper */
  protected $combatHelper;
  /** @var CombatDuel */
  protected $combat;
  /** @var CombatLogManager */
  protected $log;
  /** @var Context */
  protected $db;
  /** @var Translator */
  protected $translator;
  /** @var string */
  protected $arena;
  
  function __construct(User $user, \HeroesofAbenez\Model\CombatHelper $combatHelper, CombatDuel $combat, CombatLogManager $log, Context $db, Translator $translator) {
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