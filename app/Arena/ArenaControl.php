<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use Nette\Security\User;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Model\CombatLogManager;
use HeroesofAbenez\Combat\CombatLogger;
use Nette\Localization\Translator;
use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Combat\CombatBase;
use HeroesofAbenez\Model\CombatHelper;
use HeroesofAbenez\Model\OpponentNotFoundException;
use Nextras\Orm\Collection\ICollection;

/**
 * Basic Arena Control
 *
 * @author Jakub Konečný
 * @property-read \Nette\Bridges\ApplicationLatte\Template $template
 */
abstract class ArenaControl extends \Nette\Application\UI\Control
{
    protected const DAILY_FIGHTS_LIMIT = 10;

    protected string $arena;
    protected string $profileLink;

    public function __construct(
        protected readonly User $user,
        protected readonly CombatHelper $combatHelper,
        protected readonly CombatBase $combat,
        protected readonly CombatLogManager $log,
        protected readonly ORM $orm,
        protected readonly Translator $translator
    ) {
    }

    /**
     * Get data for specified player
     *
     * @throws OpponentNotFoundException
     */
    protected function getPlayer(int $id): Character
    {
        try {
            $player = $this->combatHelper->getPlayer($id);
        } catch (OpponentNotFoundException $e) {
            throw $e;
        }
        return $player;
    }

    abstract protected function getOpponents(): ICollection;

    public function render(): void
    {
        $this->template->setFile(__DIR__ . "/arena.latte");
        $this->template->opponents = $this->getOpponents();
        $this->template->arena = $this->arena;
        $this->template->profileLink = $this->profileLink;
        $this->template->render();
    }

    /**
     * @return int[]
     */
    abstract protected function calculateRewards(Character $player, Character $opponent): array;

    /**
     * Execute the duel
     */
    protected function doDuel(Character $opponent): void
    {
        if ($this->combatHelper->getNumberOfTodayArenaFights($this->user->id) >= self::DAILY_FIGHTS_LIMIT) {
            $this->presenter->flashMessage(
                $this->translator->translate("errors.arena.cannotFightToday", self::DAILY_FIGHTS_LIMIT)
            );
            $this->presenter->redirect("this");
        }
        $player = $this->getPlayer($this->user->id);
        $this->combat->setDuelParticipants($player, $opponent);
        $winner = $this->combat->execute();
        if ($winner === 1) {
            $rewards = $this->calculateRewards($player, $opponent);
            /** @var \HeroesofAbenez\Orm\Character $character */
            $character = $this->orm->characters->getById($this->user->id);
            $character->money += $rewards["money"];
            $character->experience += $rewards["experience"];
            $character->lastActive = new \DateTimeImmutable();
            $this->orm->characters->persistAndFlush($character);
            $params = ["playerName" => $player->name, "rewardMoney" => $rewards["money"], "rewardExperiences" => $rewards["experience"]];
            $this->combat->log->logText("texts.arena.fightRewards", $params);
        }
        $combatId = $this->saveCombat($this->combat->log, ($winner === 1));
        $this->presenter->redirect("Combat:view", ["id" => $combatId]);
    }

    /**
     * @throws OpponentNotFoundException
     */
    abstract protected function getOpponent(int $id): Character;

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function handleFight(int $id): void
    {
        try {
            $enemy = $this->getOpponent($id);
        } catch (OpponentNotFoundException) {
            throw new \Nette\Application\BadRequestException();
        }
        $this->doDuel($enemy);
    }

    /**
     * Save log from combat
     *
     * @return int Combat's id
     */
    public function saveCombat(CombatLogger $logger, bool $won): int
    {
        $this->combatHelper->bumpNumberOfTodayArenaFights($this->user->id, $won);
        return $this->log->write((string) $logger);
    }
}
