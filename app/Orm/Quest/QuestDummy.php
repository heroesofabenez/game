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
  
  function __construct(Quest $quest) {
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
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return string
   */
  function getIntroduction(): string {
    return $this->introduction;
  }
  
  /**
   * @return string
   */
  function getEndText(): string {
    return $this->endText;
  }
  
  /**
   * @return int
   */
  function getCostMoney(): int {
    return $this->costMoney;
  }
  
  /**
   * @return int|NULL
   */
  function getNeededItem(): ?int {
    return $this->neededItem;
  }
  
  /**
   * @return int|NULL
   */
  function getNeededQuest(): ?int {
    return $this->neededQuest;
  }
  
  /**
   * @return int|NULL
   */
  function getNeededLevel(): ?int {
    return $this->neededLevel;
  }
  
  /**
   * @return int
   */
  function getItemAmount(): int {
    return $this->itemAmount;
  }
  
  /**
   * @return bool
   */
  function isItemLose(): bool {
    return $this->itemLose;
  }
  
  /**
   * @return int
   */
  function getRewardMoney(): int {
    return $this->rewardMoney;
  }
  
  /**
   * @return int
   */
  function getRewardXp(): int {
    return $this->rewardXp;
  }
  
  /**
   * @return int|NULL
   */
  function getRewardItem(): ?int {
    return $this->rewardItem;
  }
  
  /**
   * @return int
   */
  function getNpcStart(): int {
    return $this->npcStart;
  }
  
  /**
   * @return int
   */
  function getNpcEnd(): int {
    return $this->npcEnd;
  }
  
  /**
   * @return int
   */
  function getOrder(): int {
    return $this->order;
  }
  
  /**
   * @return bool
   */
  function isProgress(): bool {
    return $this->progress;
  }
  
  /**
   * @param bool $progress
   */
  function setProgress(bool $progress) {
    $this->progress = $progress;
  }
}
?>