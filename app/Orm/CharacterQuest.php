<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;
use Nextras\Orm\Collection\ICollection;

/**
 * CharacterQuest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$quests}
 * @property Quest $quest {m:1 Quest, oneSided=true}
 * @property int $progress {default static::PROGRESS_STARTED}
 * @property \DateTimeImmutable $started
 * @property-read int $rewardMoney {virtual}
 * @property-read int $arenaWins {virtual}
 * @property-read int $guildDonation {virtual}
 * @property-read int $activeSkillsLevel {virtual}
 * @property-read int $friends {virtual}
 */
final class CharacterQuest extends \Nextras\Orm\Entity\Entity
{
    public const PROGRESS_OFFERED = 0;
    public const PROGRESS_STARTED = 1;
    public const PROGRESS_FINISHED = 3;

    private FriendshipsRepository $friendshipsRepository;

    public function injectFriendshipsRepository(FriendshipsRepository $friendshipsRepository): void
    {
        $this->friendshipsRepository = $friendshipsRepository;
    }

    protected function setterProgress(int $value): int
    {
        return Numbers::clamp($value, self::PROGRESS_OFFERED, self::PROGRESS_FINISHED);
    }

    protected function getterRewardMoney(): int
    {
        $reward = $this->quest->rewardMoney;
        return (int) ($reward + $reward / 100 * $this->character->charismaBonus);
    }

    protected function getterArenaWins(): int
    {
        $count = 0;
        if (!isset($this->started)) {
            return 0;
        }
        /** @var ArenaFightCount[] $arenaFights */
        $arenaFights = $this->character->arenaFights->toCollection()->findBy([
            'day>=' => $this->started->format("d.m.Y")
        ])->fetchAll();
        foreach ($arenaFights as $arenaFight) {
            $count += $arenaFight->won;
        }
        return $count;
    }

    protected function getterGuildDonation(): int
    {
        $result = 0;
        if (!isset($this->started)) {
            return 0;
        }
        /** @var ICollection|GuildDonation[] $donations */
        $donations = $this->character->guildDonations->toCollection()->findBy([
            'when>=' => $this->started,
        ])->fetchAll();
        foreach ($donations as $donation) {
            $result += $donation->amount;
        }
        return $result;
    }

    protected function getterActiveSkillsLevel(): int
    {
        $totalLevel = 0;
        if (!isset($this->started)) {
            return 0;
        }
        /** @var ICollection|CharacterAttackSkill[] $attackSkills */
        $attackSkills = $this->character->attackSkills->toCollection()->fetchAll();
        foreach ($attackSkills as $skill) {
            $totalLevel += $skill->level;
        }
        /** @var ICollection|CharacterSpecialSkill[] $specialSkills */
        $specialSkills = $this->character->specialSkills->toCollection()->fetchAll();
        foreach ($specialSkills as $skill) {
            $totalLevel += $skill->level;
        }
        return $totalLevel;
    }

    protected function getterFriends(): int
    {
        if (!isset($this->started)) {
            return 0;
        }
        return $this->friendshipsRepository->findByCharacter($this->character)->countStored();
    }

    public function onBeforeInsert(): void
    {
        parent::onBeforeInsert();
        $this->started = new \DateTimeImmutable();
    }
}
