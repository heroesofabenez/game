<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Nexendrie\Utils\Collection,
    Nette\Utils\Arrays;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property-read string $name
 * @property-read Character[] $items Characters in the team
 * @property-read Character[] $activeMembers
 * @property-read Character[] $aliveMembers
 * @property-read Character[] $usableMembers
 */
class Team extends Collection {
  protected $class = Character::class;
  /** @var string Name of the team */
  protected $name;
  
  use \Nette\SmartObject;
  
  public function __construct(string $name) {
    $this->name = $name;
  }
  
  /**
   * @return Character[]
   */
  public function getItems(): array {
    return $this->items;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  /**
   * Get member's index
   * 
   * @param int|string $id Character's id
   */
  public function getIndex($id): ?int {
    foreach($this->items as $index => $member) {
      if($member->id === $id) {
        return $index;
      }
    }
    return NULL;
  }
  
  /**
   * Check if the team has a character
   * 
   * @param string|int $id Character's id
   */
  public function hasMember($id): bool {
    return Arrays::some($this->items, function(Character $value) use($id) {
      return ($value->id === $id);
    });
  }
  
  /**
   * Get active members (alive and not stunned) from the team
   * 
   * @return Character[]
   */
  public function getActiveMembers(): array {
    return array_values(array_filter($this->items, function(Character $value) {
      return (!$value->stunned AND $value->hitpoints > 0);
    }));
  }
  
  /**
   * Get alive members from the team
   * 
   * @return Character[]
   */
  public function getAliveMembers(): array {
    return array_values(array_filter($this->items, function(Character $value) {
      return ($value->hitpoints > 0);
    }));
  }
  
  /**
   * Get members which can perform an action
   * 
   * @return Character[]
   */
  public function getUsableMembers(): array {
    return array_values(array_filter($this->items, function(Character $value) {
      return (!$value->stunned AND $value->hitpoints > 0);
    }));
  }
  
  /**
   * Check whether the team has at least 1 active member
   */
  public function hasActiveMembers(): bool {
    return count($this->getActiveMembers()) > 0;
  }
  
  /**
   * Check whether the team has at least 1 alive member
   */
  public function hasAliveMembers(): bool {
    return count($this->getAliveMembers()) > 0;
  }
}
?>