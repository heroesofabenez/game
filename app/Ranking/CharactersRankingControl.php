<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

use HeroesofAbenez\Orm\Model as ORM,
    Nextras\Orm\Collection\ICollection;

/**
 * Characters Ranking Control
 * 
 * @author Jakub Konečný
 */
class CharactersRankingControl extends RankingControl {
  /** @var ORM */
  protected $orm;
  /** @var \HeroesofAbenez\Model\Guild */
  protected $guildModel;
  
  function __construct(ORM $orm, \HeroesofAbenez\Model\Guild $guildModel) {
    $this->orm = $orm;
    $this->guildModel = $guildModel;
    parent::__construct("Characters", ["name", "level", "guild"], "Profile", "Profile");
  }
  
  /**
   * @return array
   */
  function getData(): array {
    $this->paginator->itemCount = $this->orm->characters->findAll()->countStored();
    $characters = $this->orm->characters->findAll()
      ->orderBy("level", ICollection::DESC)
      ->orderBy("experience", ICollection::DESC)
      ->orderBy("id")
      ->limitBy($this->paginator->getLength(), $this->paginator->getOffset());
    $chars = [];
    /** @var \HeroesofAbenez\Orm\Character $character */
    foreach($characters as $character) {
      if($character->guild->id == 0) {
        $guildName = "";
      } else {
        $guildName = $character->guild->name;
      }
      $chars[] = (object) [
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      ];
    }
    return $chars;
  }
}
?>