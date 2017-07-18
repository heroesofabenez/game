<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security\Permission;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
class AuthorizatorFactory {
  use \Nette\SmartObject;
  
  /** @var Permissions */
  protected $model;
  
  function __construct(Permissions $model) {
    $this->model = $model;
  }
  
  /**
  * Factory for Authorizator
  */
  function create(): Permission {
    $permission = new Permission;
    $permission->addResource("guild");
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $roles = $this->model->getRoles();
    $permissions = $this->model->getPermissions();
    
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