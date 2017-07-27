<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for quest
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $introduction
 * @property-read string $endText
 * @property-read int $costMoney
 * @property-read int|NULL $neededItem
 * @property-read int|NULL $neededQuest
 * @property-read int|NULL $neededLevel
 * @property-read int $itemAmount
 * @property-read bool $itemLose
 * @property-read int $rewardMoney
 * @property-read int $rewardXp
 * @property-read int|NULL $rewardItem
 * @property-read int $npcStart
 * @property-read int $npcEnd
 * @property-read int $order
 * @property bool $progress
 */
class QuestDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $introduction;
  /** @var string */
  protected $endText;
  /** @var int */
  protected $costMoney = 0;
  /** @var int */
  protected $neededLevel = 0;
  /** @var int|NULL */
  protected $neededQuest = NULL;
  /** @var int|NULL */
  protected $neededItem = NULL;
  /** @var int */
  protected $itemAmount;
  /** @var bool */
  protected $itemLose;
  /** @var int */
  protected $rewardMoney;
  /** @var int */
  protected $rewardXp;
  /** @var int|NULL */
  protected $rewardItem;
  /** @var int */
  protected $npcStart;
  /** @var int */
  protected $npcEnd;
  /** @var int */
  protected $order;
  /** @var bool */
  protected $progress = false;
  
  public function __construct(Quest $quest) {
    $this->id = $quest->id;
    $this->name = $quest->name;
    $this->introduction = $quest->introduction;
    $this->endText = $quest->endText;
    $this->costMoney = $quest->costMoney;
    $this->neededLevel = $quest->neededLevel;
    $this->neededQuest = ($quest->neededQuest) ? $quest->neededQuest->id : NULL;
    $this->neededItem = ($quest->neededItem) ? $quest->neededItem->id : NULL;
    $this->itemAmount = $quest->itemAmount;
    $this->itemLose = $quest->itemLose;
    $this->rewardMoney = $quest->rewardMoney;
    $this->rewardXp = $quest->rewardXp;
    $this->rewardItem = ($quest->rewardItem) ? $quest->rewardItem->id : NULL;
    $this->npcStart = $quest->npcStart->id;
    $this->npcEnd = $quest->npcEnd->id;
    $this->order = $quest->order;
  }
  
  public function getId(): int {
    return $this->id;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  public function getIntroduction(): string {
    return $this->introduction;
  }
  
  public function getEndText(): string {
    return $this->endText;
  }
  
  public function getCostMoney(): int {
    return $this->costMoney;
  }
  
  public function getNeededItem(): ?int {
    return $this->neededItem;
  }
  
  public function getNeededQuest(): ?int {
    return $this->neededQuest;
  }
  
  public function getNeededLevel(): ?int {
    return $this->neededLevel;
  }
  
  public function getItemAmount(): int {
    return $this->itemAmount;
  }
  
  public function isItemLose(): bool {
    return $this->itemLose;
  }
  
  public function getRewardMoney(): int {
    return $this->rewardMoney;
  }
  
  public function getRewardXp(): int {
    return $this->rewardXp;
  }
  
  public function getRewardItem(): ?int {
    return $this->rewardItem;
  }
  
  public function getNpcStart(): int {
    return $this->npcStart;
  }
  
  public function getNpcEnd(): int {
    return $this->npcEnd;
  }
  
  public function getOrder(): int {
    return $this->order;
  }
  
  public function isProgress(): bool {
    return $this->progress;
  }
  
  public function setProgress(bool $progress) {
    $this->progress = $progress;
  }
}
?>