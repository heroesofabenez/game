<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for npc
 *
 * @author Jakub Konečný
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $race
 * @property string $type
 * @property string $sprite
 * @property string $portrait
 * @property int $stage
 * @property int $posX
 * @property int $posY
 */
class NpcDummy {
  use \Nette\SmartObject;
  
  /** @var int id */
  protected $id;
  /** @var string name */
  protected $name;
  /** @var string description */
  protected $description;
  /** @var int id of race */
  protected $race;
  /** @var string type of npc */
  protected $type;
  /** @var string */
  protected $sprite;
  /** @var string */
  protected $portrait;
  /** @var int id of stage */
  protected $stage;
  /** @var int */
  protected $posX;
  /** @var int */
  protected $posY;
  
  function __construct(Npc $npc) {
    $this->id = $npc->id;
    $this->name = $npc->name;
    $this->description = $npc->description;
    $this->race = $npc->race->id;
    $this->type = $npc->type;
    $this->sprite = $npc->sprite;
    $this->portrait = $npc->portrait;
    $this->stage = $npc->stage->id;
    $this->posX = $npc->posX;
    $this->posY = $npc->posY;
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
  function getDescription(): string {
    return $this->description;
  }
  
  /**
   * @return int
   */
  function getRace(): int {
    return $this->race;
  }
  
  /**
   * @return string
   */
  function getType(): string {
    return $this->type;
  }
  
  /**
   * @return string
   */
  function getSprite(): string {
    return $this->sprite;
  }
  
  /**
   * @return string
   */
  function getPortrait(): string {
    return $this->portrait;
  }
  
  /**
   * @return int
   */
  function getStage(): int {
    return $this->stage;
  }
  
  /**
   * @return int
   */
  function getPosX(): int {
    return $this->posX;
  }
  
  /**
   * @return int
   */
  function getPosY(): int {
    return $this->posY;
  }
}
?>