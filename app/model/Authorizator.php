<?php
namespace HeroesofAbenez;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
class Authorizator extends \Nette\Object {
  /**
   * Get roles (from db or cache)
   * 
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function getRoles(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.permissions");
    $roles = $cache->load("roles");
    if($roles === NULL) {
      $db = $container->getService("database.default.context");
      $rolesRows = $db->table("guild_ranks")->order("id");
      foreach($rolesRows as $i => $row) {
        $roles[$i] = array("id" => $row->id, "name" => $row->name);
      }
      $cache->save("roles", $roles);
    }
    return $roles;
  }
  
  /**
   * Get permissions (from db or cache)
   * 
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function getPermissions(\Nette\Di\Container $container) {
    $roles = Authorizator::getRoles($container);
    $cache = $container->getService("caches.permissions");
    $permissions = $cache->load("permissions");
    if($permissions === NULL) {
      $db = $container->getService("database.default.context");
      $privileges = $db->table("guild_privileges");
      foreach($privileges as $row) {
        $role = $roles[$row->rank];
        $permissions[$row->id] = array("role" => $role["name"], "action" => $row->action);
      }
      $cache->save("permissions", $permissions);
    }
    return $permissions;
  }
  
  /**
  * Factory for Authorizator
  * 
  * @param \Nette\Di\Container $container
  * @return \Nette\Security\Permission
  */
  static function create(\Nette\Di\Container $container) {
    $permission = new \Nette\Security\Permission;
    $permission->addResource("guild");
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $roles = Authorizator::getRoles($container);
    $permissions = Authorizator::getPermissions($container);
    
    foreach($roles as $i => $row) {
      if($row["id"] == 1) {
        $parent = "player";
      } else {
        $parentRow = $roles[$i-1];
        $parent = $parentRow["name"];
      }
      $permission->addRole($row["name"], $parent);
    }
    
    foreach($permissions as $row) {
      $permission->allow($row["role"], "guild", $row["action"]);
    }
    return $permission;
  }
}
