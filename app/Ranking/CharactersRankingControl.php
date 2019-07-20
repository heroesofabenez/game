<?php
declare(strict_types=1);

namespace HeroesofAbenez\Ranking;

use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Collection\ICollection;

/**
 * Characters Ranking Control
 * 
 * @author Jakub Konečný
 */
final class CharactersRankingControl extends RankingControl {
  /** @var ORM */
  protected $orm;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
    parent::__construct("Characters", ["name", "level", "guild"], "Profile", "profile");
  }
  
  public function getData(): array {
    $this->paginator->itemCount = $this->orm->characters->findAll()->countStored();
    $characters = $this->orm->characters->findAll()
      ->orderBy("level", ICollection::DESC)
      ->orderBy("experience", ICollection::DESC)
      ->orderBy("id")
      ->limitBy($this->paginator->getLength(), $this->paginator->getOffset());
    $chars = [];
    /** @var \HeroesofAbenez\Orm\Character $character */
    foreach($characters as $character) {
      $guildName = "";
      if($character->guild !== null) {
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