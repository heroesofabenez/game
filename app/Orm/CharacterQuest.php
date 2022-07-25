<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

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
 */
final class CharacterQuest extends \Nextras\Orm\Entity\Entity {
  public const PROGRESS_OFFERED = 0;
  public const PROGRESS_STARTED = 1;
  public const PROGRESS_FINISHED = 3;

  protected function setterProgress(int $value): int {
    return Numbers::range($value, static::PROGRESS_OFFERED, static::PROGRESS_FINISHED);
  }

  protected function getterRewardMoney(): int {
    $reward = $this->quest->rewardMoney;
    return (int) ($reward + $reward / 100 * $this->character->charismaBonus);
  }

  protected function getterArenaWins() {
    $count = 0;
    if(!isset($this->started)) {
      return 0;
    }
    /** @var ArenaFightCount[] $arenaFights */
    $arenaFights = $this->character->arenaFights->toCollection()->findBy([
      'day>=' => $this->started->format("d.m.Y")
    ])->fetchAll();
    foreach($arenaFights as $arenaFight) {
      $count += $arenaFight->won;
    }
    return $count;
  }

  protected function getterGuildDonation() {
    $result = 0;
    if(!isset($this->started)) {
      return 0;
    }
    $donations = $this->character->guildDonations->toCollection()->findBy([
      'when>=' => $this->started,
    ])->fetchAll();
    foreach($donations as $donation) {
      $result += $donation->amount;
    }
    return $result;
  }

  public function onBeforeInsert(): void {
    parent::onBeforeInsert();
    $this->started = new \DateTimeImmutable();
  }
}
?>