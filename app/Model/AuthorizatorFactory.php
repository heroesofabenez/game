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
  
  public function __construct(Permissions $model) {
    $this->model = $model;
  }
  
  /**
  * Factory for Authorizator
  */
  public function create(): Permission {
    $permission = new Permission;
    $permission->addResource("guild");
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $roles = $this->model->getRoles();
    $permissions = $this->model->getPermissions();
    
    foreach($roles as $i => $row) {
      $parent = "player";
      if($row["id"] !== 1) {
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