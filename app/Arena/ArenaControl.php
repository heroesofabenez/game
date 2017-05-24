<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use Nette\Security\User,
    HeroesofAbenez\Orm\Model as ORM,
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
  const DAILY_FIGHTS_LIMIT = 10;
  
  /** @var User */
  protected $user;
  /** @var \HeroesofAbenez\Model\CombatHelper */
  protected $combatHelper;
  /** @var CombatDuel */
  protected $combat;
  /** @var CombatLogManager */
  protected $log;
  /** @var ORM */
  protected $orm;
  /** @var Translator */
  protected $translator;
  /** @var string */
  protected $arena;
  
  function __construct(User $user, \HeroesofAbenez\Model\CombatHelper $combatHelper, CombatDuel $combat, CombatLogManager $log, ORM $orm, Translator $translator) {
    parent::__construct();
    $this->user = $user;
    $this->combatHelper = $combatHelper;
    $this->combat = $combat;
    $this->log = $log;
    $this->orm = $orm;
    $this->translator = $translator;
  }
  
  /**
   * Get data for specified player
   * 
   * @param int $id Player's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  protected function getPlayer(int $id): Character {
    try {
      $player = $this->combatHelper->getPlayer($id);
    } catch(OpponentNotFoundException $e) {
      throw $e;
    }
    return $player;
  }
  
  abstract protected function getOpponents();
  
  /**
   * @return void
   */
  function render(): void {
    $this->template->setFile(__DIR__ . "/arena.latte");
    $this->template->opponents = $this->getOpponents();
    $this->template->arena = $this->arena;
    $this->template->render();
  }
  
  /**
   * @param Character $player
   * @param Character $opponent
   * @return int[]
   */
  abstract protected function calculateRewards(Character $player, Character $opponent): array;
  
  /**
   * Execute the duel
   * 
   * @param Character $opponent Opponent
   * @return void
   */
  protected function doDuel(Character $opponent): void {
    if($this->combatHelper->getNumberOfTodayArenaFights($this->user->id) >= self::DAILY_FIGHTS_LIMIT) {
      $this->presenter->flashMessage($this->translator->translate("errors.arena.cannotFightToday", self::DAILY_FIGHTS_LIMIT));
      $this->presenter->redirect("this");
    }
    $player = $this->getPlayer($this->user->id);
    $this->combat->setParticipants($player, $opponent);
    $winner = $this->combat->execute();
    if($winner === 1) {
      $rewards = $this->calculateRewards($player, $opponent);
      $character = $this->orm->characters->getById($this->user->id);
      $character->money += $rewards["money"];
      $character->experience += $rewards["experience"];
      $this->orm->characters->persistAndFlush($character);
      $this->combat->log->logText("$player->name gets {$rewards["money"]} silver marks and {$rewards["experience"]} experiences.");
    }
    $combatId = $this->saveCombat($this->combat->log);
    $this->presenter->redirect("Combat:view", ["id" => $combatId]);
  }
  
  /**
   * @param int $id
   * @return void
   */
  abstract function handleFight(int $id): void;
  
  /**
   * Save log from combat
   * 
   * @param CombatLogger $logger
   * @return int Combat's id
   */
  function saveCombat(CombatLogger $logger): int {
    $this->combatHelper->bumpNumberOfTodayArenaFights($this->user->id);
    $log = (string) $logger;
    return $this->log->write($log);
  }
}
?>