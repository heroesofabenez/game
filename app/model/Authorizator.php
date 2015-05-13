<?php
namespace HeroesofAbenez;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
class Authorizator extends \Nette\Object {
  /**
  * @return \Nette\Security\Permission
  */
  static function create(\Nette\Database\Context $db) {
    $permission = new \Nette\Security\Permission;
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    $roles = $db->table("guild_ranks")->order("id");
    foreach($roles as $i => $row) {
      if($row->id == 1) {
        $parent = "player";
      } else {
        $parentRow = $roles[$i-1];
        $parent = $parentRow->name;
      }
      $permission->addRole($row->name, $parent);
    }
    
    $permission->addResource("guild");
    
    $privileges = $db->table("guild_privileges");
    foreach($privileges as $row) {
      $role = $roles[$row->rank];
      $permission->allow($role->name, "guild", $row->action);
    }
    return $permission;
  }
}
