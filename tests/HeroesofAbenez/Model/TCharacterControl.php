<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security\User;
use \HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\Character;

/**
 * @author Jakub Konečný
 */
trait TCharacterControl {
  use \Testbench\TCompiledContainer;

  protected function getCharacter(): Character {
    /** @var User $user */
    $user = $this->getService(User::class);
    /** @var ORM $orm */
    $orm = $this->getService(ORM::class);
    return $orm->characters->getById($user->id);
  }

  /**
   * @return mixed
   */
  protected function getCharacterStat(string $stat) {
    return $this->getCharacter()->$stat;
  }

  /**
   * Perform an action and revert some stats to original values
   *
   * @param string[] $stats
   */
  protected function preserveStats(array $stats, callable $callback): void {
    /** @var User $user */
    $user = $this->getService(User::class);
    /** @var ORM $orm */
    $orm = $this->getService(ORM::class);
    $data = $this->getCharacter();
    $oldStats = [];
    foreach($stats as $stat) {
      $oldStats[$stat] = $data->$stat;
    }
    $orm->characters->persistAndFlush($data);
    $user->login("");
    try {
      $callback();
    } finally {
      foreach($oldStats as $stat => $oldValue) {
        $data->$stat = $oldValue;
      }
      $orm->characters->persistAndFlush($data);
      $user->login("");
    }
  }

  /**
   * Modify the character and perform some action with modified stats
   */
  protected function modifyCharacter(array $stats, callable $callback): void {
    /** @var User $user */
    $user = $this->getService(User::class);
    /** @var ORM $orm */
    $orm = $this->getService(ORM::class);
    $data = $this->getCharacter();
    $oldStats = [];
    foreach($stats as $stat => $newValue) {
      $oldStats[$stat] = $data->$stat;
      $data->$stat = $newValue;
    }
    $orm->characters->persistAndFlush($data);
    $user->login("");
    try {
      $callback();
    } finally {
      foreach($oldStats as $stat => $oldValue) {
        $data->$stat = $oldValue;
      }
      foreach($data->items as $item) {
        if(!isset($item->id)) {
          $data->items->remove($item);
        }
      }
      $orm->characters->persistAndFlush($data);
      $user->login("");
    }
  }
}
?>