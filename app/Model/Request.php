<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Request as RequestEntity;
use Nette\NotImplementedException;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
final class Request {
  use \Nette\SmartObject;

  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  /** @var Profile */
  protected $profileModel;
  /** @var Guild */
  protected $guildModel;
  
  public function __construct(\Nette\Security\User $user, ORM $orm, Profile $profileModel, Guild $guildModel) {
    $this->user = $user;
    $this->orm = $orm;
    $this->profileModel = $profileModel;
    $this->guildModel = $guildModel;
  }
  
  /**
   * Can player see the request?
   */
  public function canShow(RequestEntity $request): bool {
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
      case RequestEntity::TYPE_GROUP_JOIN:
        return ($request->from == $this->user->id OR $request->to->id == $this->user->id);
      case RequestEntity::TYPE_GUILD_JOIN:
        if($request->to->id == $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->from->id);
        $guild = (!is_null($leader->guild)) ? $leader->guild->id : null;
        return ($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite"));
      case RequestEntity::TYPE_GUILD_APP:
        if($request->from->id === $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->to->id);
        $guild = (!is_null($leader->guild)) ? $leader->guild->id : null;
        return ($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite"));
    }
    return false;
  }
  
  /**
   * Can player accept/decline the request?
   */
  public function canChange(RequestEntity $request): bool {
    if($request->from->id == $this->user->id) {
      return false;
    }
    if($request->to->id == $this->user->id) {
      return true;
    }
    if($request->type == "guild_app") {
      $guild = (!is_null($request->to->guild)) ? $request->to->guild->id : null;
      return ($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite"));
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
    if(is_null($request)) {
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
    try {
      $request = $this->show($id);
    } catch(RequestNotFoundException $e) {
      throw $e;
    }
    if(!$this->canShow($request)) {
      throw new CannotSeeRequestException();
    }
    if(!$this->canChange($request)) {
      throw new CannotAcceptRequestException();
    }
    if($request->status !== RequestEntity::STATUS_NEW) {
      throw new RequestAlreadyHandledException();
    }
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
      case RequestEntity::TYPE_GROUP_JOIN:
        throw new NotImplementedException();
      case RequestEntity::TYPE_GUILD_APP:
        $uid = $request->from->id;
        $gid = (!is_null($request->to->guild)) ? $request->to->guild->id : null;
        $this->guildModel->join($uid, $gid);
        break;
      case RequestEntity::TYPE_GUILD_JOIN:
        $uid = $request->to->id;
        $gid = (!is_null($request->from->guild)) ? $request->from->guild->id : null;
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
    try {
      $request = $this->show($id);
    } catch(RequestNotFoundException $e) {
      throw $e;
    }
    if(!$this->canShow($request)) {
      throw new CannotSeeRequestException();
    }
    if(!$this->canChange($request)) {
      throw new CannotDeclineRequestException();
    }
    if($request->status !== RequestEntity::STATUS_NEW) {
      throw new RequestAlreadyHandledException();
    }
    $request->status = RequestEntity::STATUS_DECLINED;
    $this->orm->requests->persistAndFlush($request);
  }
}
?>