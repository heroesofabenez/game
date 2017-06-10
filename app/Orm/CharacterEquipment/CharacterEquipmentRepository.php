<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterEquipmentRepository
 *
 * @author Jakub Konečný
 */
class CharacterEquipmentRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterEquipment::class];
  }
  
  /**
   * @param int $id
   * @return CharacterEquipment|NULL
   */
  function getById($id): ?CharacterEquipment {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param string $slot
   * @return ICollection|CharacterEquipment[]
   */
  function findByCharacterAndSlot($character, string $slot): ICollection {
    return $this->findBy([
      "character" => $character,
      "this->item->slot" => $slot
    ]);
  }
  
  /**
   * @param Character|int $character
   * @return ICollection|CharacterEquipment[]
   */
  function findCharactersEquipment($character): ICollection {
    return $this->findBy([
      "character" => $character,
      "worn" => true
    ]);
  }
}
?>