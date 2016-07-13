<?php
namespace HeroesofAbenez\Model;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
class AuthorizatorFactory {
  use \Nette\StaticClass;
  
  /**
  * Factory for Authorizator
  * 
  * @param \HeroesofAbenez\Model\Permissions $model
  * @return \Nette\Security\Permission
  */
  static function create(Permissions $model) {
    $permission = new \Nette\Security\Permission;
    $permission->addResource("guild");
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $roles = $model->getRoles();
    $permissions = $model->getPermissions();
    
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
?>