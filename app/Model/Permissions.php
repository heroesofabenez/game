<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\GuildPrivilege;

/**
 * Permissions Model
 *
 * @author Jakub Konečný
 */
final class Permissions {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  public function __construct(ORM $orm, \Nette\Caching\Cache $cache) {
    $this->orm = $orm;
    $this->cache = $cache;
  }
  
  /**
   * Get roles (from db or cache)
   *
   * @return string[]
   */
  public function getRoles(): array {
    /** @var string[] $roles */
    $roles = $this->cache->load("roles", function(): array {
      $rows = $this->orm->guildRanks->findAll()->orderBy("id");
      return $rows->fetchPairs("id", "name");
    });
    return $roles;
  }

  public function getRankId(string $name): ?int {
    $roles = $this->getRoles();
    foreach($roles as $index => $role) {
      if($role === $name) {
        return $index;
      }
    }
    return null;
  }
  
  /**
   * Get permissions (from db or cache)
   * 
   * @return array[]
   */
  public function getPermissions(): array {
    $roles = $this->getRoles();
    $permissions = $this->cache->load("permissions", function() use($roles): array {
      $permissions = [];
      $privileges = $this->orm->guildPrivileges->findAll();
      /** @var GuildPrivilege $privilege */
      foreach($privileges as $privilege) {
        $role = $roles[$privilege->rank->id];
        $permissions[$privilege->id] = ["role" => $role, "action" => $privilege->action];
      }
      return $permissions;
    });
    return $permissions;
  }
}
?>