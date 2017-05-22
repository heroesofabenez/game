<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Request as RequestEntity,
    Nette\NotImplementedException,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Request Model
 * 
 * @author Jakub Konečný
 */
class Request {
  use \Nette\SmartObject;
  
   /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  /** @var Profile */
  protected $profileModel;
  /** @var Guild */
  protected $guildModel;
  
  function __construct(\Nette\Security\User $user, ORM $orm, Profile $profileModel, Guild $guildModel) {
    $this->user = $user;
    $this->orm = $orm;
    $this->profileModel = $profileModel;
    $this->guildModel = $guildModel;
  }
  
  /**
   * Can player see the request?
   * 
   * @param int $requestId
   * @return bool
   */
  function canShow(int $requestId): bool {
    $request = $this->orm->requests->getById($requestId);
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
      case RequestEntity::TYPE_GROUP_JOIN:
        if($request->from == $this->user->id OR $request->to->id == $this->user->id) {
          return true;
        } else {
          return false;
        }
        break;
      case RequestEntity::TYPE_GUILD_JOIN:
        if($request->to->id == $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->from->id);
        $guild = $leader->guild->id;
        if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
          return true;
        } else {
          return false;
        }
        break;
      case RequestEntity::TYPE_GUILD_APP:
        if($request->from->id === $this->user->id) {
          return true;
        }
        $leader = $this->orm->characters->getById($request->to->id);
        $guild = $leader->guild->id;
        if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
          return true;
        } else {
          return false;
        }
        break;
    }
    return false;
  }
  
  /**
   * Can player accept/decline the request?
   * 
   * @param int $requestId
   * @return bool
   */
  function canChange(int $requestId): bool {
    $request = $this->orm->requests->getById($requestId);
    if($request->from->id == $this->user->id) {
      return false;
    }
    if($request->to->id == $this->user->id) {
      return true;
    }
    if($request->type == "guild_app") {
      $guild = $request->to->guild->id;
      if($this->user->identity->guild == $guild AND $this->user->isAllowed("guild", "invite")) {
        return true;
      } else {
        return false;
      }
    }
    return false;
  }
  
  /**
   * Gets data about specified request
   * 
   * @param int $id Request's id
   * @return RequestEntity
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   */
  function show(int $id): RequestEntity {
    $request = $this->orm->requests->getById($id);
    if(is_null($request)) {
      throw new RequestNotFoundException;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    return $request;
  }
  
  /**
   * Accept specified request
   * 
   * @param int $id Request's id
   * @return void
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotAcceptRequestException
   * @throws RequestAlreadyHandledException
   * @throws NotImplementedException
   */
  function accept(int $id): void {
    try {
      $request = $this->show($id);
    } catch(RequestNotFoundException $e) {
      throw $e;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    if(!$this->canChange($id)) {
      throw new CannotAcceptRequestException;
    }
    if($request->status !== "new") {
      throw new RequestAlreadyHandledException;
    }
    switch($request->type) {
      case RequestEntity::TYPE_FRIENDSHIP:
      case RequestEntity::TYPE_GROUP_JOIN:
        throw new NotImplementedException;
        break;
      case RequestEntity::TYPE_GUILD_APP:
        $uid = $request->from->id;
        $gid = $request->to->guild->id;
        $this->guildModel->join($uid, $gid);
        break;
      case RequestEntity::TYPE_GUILD_JOIN:
        $uid = $request->to->id;
        $gid = $request->from->guild->id;
        $this->guildModel->join($uid, $gid);
        break;
    }
    $request->status = RequestEntity::STATUS_ACCEPTED;
    $this->orm->requests->persistAndFlush($request);
  }
  
  /**
   * Decline specified request
   * 
   * @param int $id Request's id
   * @return void
   * @throws RequestNotFoundException
   * @throws CannotSeeRequestException
   * @throws CannotDeclineRequestException
   * @throws RequestAlreadyHandledException
   */
  function decline(int $id): void {
    try {
      $request = $this->show($id);
    } catch(RequestNotFoundException $e) {
      throw $e;
    }
    if(!$this->canShow($id)) {
      throw new CannotSeeRequestException;
    }
    if(!$this->canChange($id)) {
      throw new CannotDeclineRequestException;
    }
    if($request->status !== RequestEntity::STATUS_NEW) {
      throw new RequestAlreadyHandledException;
    }
    $request->status = RequestEntity::STATUS_DECLINED;
    $this->orm->requests->persistAndFlush($request);
  }
}
?>