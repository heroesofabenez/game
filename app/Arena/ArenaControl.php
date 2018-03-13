<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use Nette\Security\User,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Model\CombatLogManager,
    HeroesofAbenez\Model\CombatLogger,
    Nette\Localization\ITranslator,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Model\CombatBase,
    HeroesofAbenez\Model\CombatHelper,
    HeroesofAbenez\Model\OpponentNotFoundException,
    Nextras\Orm\Collection\ICollection;

/**
 * Basic Arena Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class ArenaControl extends \Nette\Application\UI\Control {
  protected const DAILY_FIGHTS_LIMIT = 10;
  
  /** @var User */
  protected $user;
  /** @var CombatHelper */
  protected $combatHelper;
  /** @var CombatBase */
  protected $combat;
  /** @var CombatLogManager */
  protected $log;
  /** @var ORM */
  protected $orm;
  /** @var ITranslator */
  protected $translator;
  /** @var string */
  protected $arena;
  
  public function __construct(User $user, CombatHelper $combatHelper, CombatBase $combat, CombatLogManager $log, ORM $orm, ITranslator $translator) {
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
  
  abstract protected function getOpponents(): ICollection;
  
  public function render(): void {
    $this->template->setFile(__DIR__ . "/arena.latte");
    $this->template->opponents = $this->getOpponents();
    $this->template->arena = $this->arena;
    $this->template->render();
  }
  
  /**
   * @return int[]
   */
  abstract protected function calculateRewards(Character $player, Character $opponent): array;
  
  /**
   * Execute the duel
   */
  protected function doDuel(Character $opponent): void {
    if($this->combatHelper->getNumberOfTodayArenaFights($this->user->id) >= self::DAILY_FIGHTS_LIMIT) {
      $this->presenter->flashMessage($this->translator->translate("errors.arena.cannotFightToday", self::DAILY_FIGHTS_LIMIT));
      $this->presenter->redirect("this");
    }
    $player = $this->getPlayer($this->user->id);
    $this->combat->setDuelParticipants($player, $opponent);
    $winner = $this->combat->execute();
    if($winner === 1) {
      $rewards = $this->calculateRewards($player, $opponent);
      /** @var \HeroesofAbenez\Orm\Character $character */
      $character = $this->orm->characters->getById($this->user->id);
      $character->money += $rewards["money"];
      $character->experience += $rewards["experience"];
      $this->orm->characters->persistAndFlush($character);
      $this->combat->log->logText("$player->name gets {$rewards["money"]} silver marks and {$rewards["experience"]} experiences.");
    }
    $combatId = $this->saveCombat($this->combat->log);
    $this->presenter->redirect("Combat:view", ["id" => $combatId]);
  }
  
  abstract public function handleFight(int $id): void;
  
  /**
   * Save log from combat
   *
   * @return int Combat's id
   */
  public function saveCombat(CombatLogger $logger): int {
    $this->combatHelper->bumpNumberOfTodayArenaFights($this->user->id);
    $log = (string) $logger;
    return $this->log->write($log);
  }
}
?>