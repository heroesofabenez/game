<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Friendship;
use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Collection\ICollection;

/**
 * Model Friends
 *
 * @author Jakub Konečný
 */
final class Friends {
  use \Nette\SmartObject;

  public function __construct(private ORM $orm, private \Nette\Security\User $user) {
  }

  private function getFriendship(int $character): ?Friendship {
    return $this->orm->friendships->getBy([
      ICollection::OR,
      [
        "character1" => $this->user->id,
        "character2" => $character,
      ],
      [
        "character1" => $character,
        "character2" => $this->user->id,
      ],
    ]);
  }

  public function isFriendsWith(int $character): bool {
    if($character === $this->user->id) {
      return true;
    }
    return ($this->getFriendship($character) !== null);
  }

  /**
   * @throws AlreadyFriendsException
   * @throws FriendshipRequestAlreadySentException
   */
  public function befriend(int $character): void {
    if($this->isFriendsWith($character)) {
      throw new AlreadyFriendsException();
    }
    if((null !== $this->orm->requests->getBy([
      ICollection::OR,
      [
        "from" => $this->user->id,
        "to" => $character,
        "type" => \HeroesofAbenez\Orm\Request::TYPE_FRIENDSHIP,
        "status" => \HeroesofAbenez\Orm\Request::STATUS_NEW,
      ],
      [
        "from" => $character,
        "to" => $this->user->id,
        "type" => \HeroesofAbenez\Orm\Request::TYPE_FRIENDSHIP,
        "status" => \HeroesofAbenez\Orm\Request::STATUS_NEW,
      ],
    ]))) {
      throw new FriendshipRequestAlreadySentException();
    }
    $request = new \HeroesofAbenez\Orm\Request();
    $this->orm->requests->attach($request);
    $request->from = $this->user->id;
    $request->to = $character;
    $request->type = \HeroesofAbenez\Orm\Request::TYPE_FRIENDSHIP;
    $this->orm->requests->persistAndFlush($request);
  }

  /**
   * @throws NotFriendsException
   */
  public function remove(int $character): void {
    $friendship = $this->getFriendship($character);
    if($friendship === null) {
      throw new NotFriendsException();
    }
    $this->orm->friendships->removeAndFlush($friendship);
  }
}
?>