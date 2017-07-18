<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\GuildRank,
    HeroesofAbenez\Orm\GuildPrivilege;

/**
 * Permissions Model
 *
 * @author Jakub Konečný
 */
class Permissions {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  function __construct(ORM $orm, \Nette\Caching\Cache $cache) {
    $this->orm = $orm;
    $this->cache = $cache;
  }
  
  /**
   * Get roles (from db or cache)
   */
  function getRoles(): array {
    $roles = $this->cache->load("roles", function(& $dependencies) {
      $roles = [];
      $rows = $this->orm->guildRanks->findAll()->orderBy("id");
      /** @var GuildRank $row */
      foreach($rows as $row) {
        $roles[$row->id] = ["id" => $row->id, "name" => $row->name];
      }
      return $roles;
    });
    return $roles;
  }
  
  /**
   * Get name of specified rank
   */
  function getRoleName(int $id): string {
    $ranks = $this->getRoles();
    return $ranks[$id]["name"];
  }
  
  /**
   * Get permissions (from db or cache)
   * 
   * @return \stdClass[]
   */
  function getPermissions(): array {
    $roles = $this->getRoles();
    $permissions = $this->cache->load("permissions", function(& $dependencies) use($roles) {
      $permissions = [];
      $privileges = $this->orm->guildPrivileges->findAll();
      /** @var GuildPrivilege $privilege */
      foreach($privileges as $privilege) {
        $role = $roles[$privilege->rank->id];
        $permissions[$privilege->id] = ["role" => $role["name"], "action" => $privilege->action];
      }
      return $permissions;
    });
    return $permissions;
  }
}
?>