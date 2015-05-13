<?php
namespace HeroesofAbenez;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
class Authorizator extends \Nette\Object {
  /**
   * Get roles from db
   * 
   * @param \Nette\Database\Context $db
   * @return array
   */
  static function getRoles(\Nette\Database\Context $db) {
    $rolesRows = $db->table("guild_ranks")->order("id");
    foreach($rolesRows as $i => $row) {
      $roles[$i] = array("id" => $row->id, "name" => $row->name);
    }
    return $roles;
  }
  
  static function getPermissions(\Nette\Database\Context $db, $roles) {
    $privileges = $db->table("guild_privileges");
    foreach($privileges as $row) {
      $role = $roles[$row->rank];
      $permissions[$row->id] = array("role" => $role["name"], "action" => $row->action);
    }
    return $permissions;
  }
  
  /**
  * @return \Nette\Security\Permission
  */
  static function create(\Nette\Di\Container $container) {
    $permission = new \Nette\Security\Permission;
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $cache = $container->getService("permissionsCache");
    $db = $container->getService("database.default.context");
    $roles = $cache->load("roles");
    $permissions = $cache->load("permissions");
    $permission->addResource("guild");
    
    if($roles === NULL OR $permissions === NULL) {
      $roles = Authorizator::getRoles($db);
      $permissions = Authorizator::getPermissions($db, $roles);
      $cache->save("roles", $roles);
      $cache->save("permissions", $permissions);
    }
    
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
