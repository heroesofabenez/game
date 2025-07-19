<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Character as CharacterEntity;
use HeroesofAbenez\Orm\Friendship;
use HeroesofAbenez\Orm\Request as RequestEntity;
use Nette\NotImplementedException;
use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Collection\ICollection;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
final class Request {
  use \Nette\SmartObject;
  
  public function __construct(private \Nette\Security\User $user, private ORM $orm, private Guild $guildModel) {
  }
  
  /**
   * Can player see the request?
   */
  public function canShow(RequestEntity $request): bool {
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
      case RequestEntity::TYPE_GROUP_JOIN:
        return ($request->from->id === $this->user->id || $request->to->id === $this->user->id);
      case RequestEntity::TYPE_GUILD_JOIN:
        if($request->to->id === $this->user->id) {
          return true;
        }
        /** @var CharacterEntity $leader */
        $leader = $this->orm->characters->getById($request->from->id);
        $guild = ($leader->guild !== null) ? $leader->guild->id : null;
        return ($this->user->identity->guild === $guild && $this->user->isAllowed("guild", "invite"));
      case RequestEntity::TYPE_GUILD_APP:
        if($request->from->id === $this->user->id) {
          return true;
        }
        /** @var CharacterEntity $leader */
        $leader = $this->orm->characters->getById($request->to->id);
        $guild = ($leader->guild !== null) ? $leader->guild->id : null;
        return ($this->user->identity->guild === $guild && $this->user->isAllowed("guild", "invite"));
    }
    return false;
  }
  
  /**
   * Can player accept/decline the request?
   */
  public function canChange(RequestEntity $request): bool {
    if($request->from->id === $this->user->id) {
      return false;
    }
    if($request->to->id === $this->user->id) {
      return true;
    }
    if($request->type === "guild_app") {
      $guild = ($request->to->guild !== null) ? $request->to->guild->id : null;
      return ($this->user->identity->guild === $guild && $this->user->isAllowed("guild", "invite"));
    }
    return false;
  }
  
  /**
   * Gets data about specified request
   *
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   */
  public function show(int $id): RequestEntity {
    $request = $this->orm->requests->getById($id);
    if($request === null) {
      throw new RequestNotFoundException();
    }
    if(!$this->canShow($request)) {
      throw new CannotSeeRequestException();
    }
    return $request;
  }
  
  /**
   * Accept specified request
   *
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotAcceptRequestException
   * @throws RequestAlreadyHandledException
   * @throws NotImplementedException
   */
  public function accept(int $id): void {
    $request = $this->show($id);
    if(!$this->canChange($request)) {
      throw new CannotAcceptRequestException();
    }
    if($request->status !== RequestEntity::STATUS_NEW) {
      throw new RequestAlreadyHandledException();
    }
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
        $friendship = new Friendship();
        $this->orm->friendships->attach($friendship);
        $friendship->character1 = $request->from->id;
        $friendship->character2 = $request->to->id;
        $this->orm->friendships->persist($friendship);
        break;
      case RequestEntity::TYPE_GROUP_JOIN:
        throw new NotImplementedException();
      case RequestEntity::TYPE_GUILD_APP:
        $uid = $request->from->id;
        $gid = ($request->to->guild !== null) ? $request->to->guild->id : 0;
        $this->guildModel->join($uid, $gid);
        break;
      case RequestEntity::TYPE_GUILD_JOIN:
        $uid = $request->to->id;
        $gid = ($request->from->guild !== null) ? $request->from->guild->id : 0;
        $this->guildModel->join($uid, $gid);
        break;
    }
    $request->status = RequestEntity::STATUS_ACCEPTED;
    $this->orm->requests->persistAndFlush($request);
  }
  
  /**
   * Decline specified request
   *
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotDeclineRequestException
   * @throws RequestAlreadyHandledException
   */
  public function decline(int $id): void {
    $request = $this->show($id);
    if(!$this->canChange($request)) {
      throw new CannotDeclineRequestException();
    }
    if($request->status !== RequestEntity::STATUS_NEW) {
      throw new RequestAlreadyHandledException();
    }
    $request->status = RequestEntity::STATUS_DECLINED;
    $this->orm->requests->persistAndFlush($request);
  }

  /**
   * @return ICollection|RequestEntity[]
   */
  public function listOfRequests(): ICollection {
    return $this->orm->requests->findBy([
      ICollection::AND,
      ["status" => RequestEntity::STATUS_NEW],
      [
        ICollection::OR,
        "from" => $this->user->id,
        "to" => $this->user->id,
      ],
    ])->orderBy("sent", ICollection::DESC);
  }
}
?>