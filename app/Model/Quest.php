<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays;
use HeroesofAbenez\Orm\QuestDummy as QuestEntity;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Quest Model
 * 
 * @author Jakub Konečný
 */
final class Quest {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(\Nette\Caching\Cache $cache, ORM $orm,  \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  /**
   * Gets list of quests
   * 
   * @param int $npc Return quests only from certain npc, 0 = all npcs
   * @return QuestEntity[]
   */
  public function listOfQuests(int $npc = 0): array {
    $quests = $this->cache->load("quests", function() {
      $return = [];
      $quests = $this->orm->quests->findAll();
      /** @var \HeroesofAbenez\Orm\Quest $quest */
      foreach($quests as $quest) {
        $return[$quest->id] = new QuestEntity($quest);
      }
      return $return;
    });
    if($npc > 0) {
      foreach($quests as $quest) {
        if($quest->npcStart !== $npc OR $quest->npcEnd !== $npc) {
          unset($quests[$quest->id]);
        }
      }
    }
    return $quests;
  }
  
  /**
   * Gets info about specified quest
   */
  public function view(int $id): ?QuestEntity {
    $quests = $this->listOfQuests();
    return Arrays::get($quests, $id, null);
  }
  
  /**
   * Get quest's status
   */
  public function status(int $id): int {
    $row = $this->orm->characterQuests->getByCharacterAndQuest($this->user->id, $id);
    if(is_null($row)) {
      return \HeroesofAbenez\Orm\CharacterQuest::PROGRESS_OFFERED;
    }
    return $row->progress;
  }
  
  /**
   * Checks if the player finished specified quest
   */
  public function isFinished(int $id): bool {
    $status = $this->status($id);
    return ($status >= \HeroesofAbenez\Orm\CharacterQuest::PROGRESS_FINISHED);
  }
}
?>