<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Security\Permission;

/**
 * Authorizator for the game
 *
 * @author Jakub Konečný
 */
final class AuthorizatorFactory {
  use \Nette\SmartObject;

  protected Permissions $model;
  
  public function __construct(Permissions $model) {
    $this->model = $model;
  }

  /**
   * Factory for Authorizator
   */
  public function create(): Permission {
    $permission = new Permission();
    $permission->addResource("guild");
    $permission->addRole("guest");
    $permission->addRole("player", "guest");
    
    $roles = $this->model->getRoles();
    $permissions = $this->model->getPermissions();

    /**
     * @var int $index
     * @var string $role
     */
    foreach($roles as $index => $role) {
      $parent = "player";
      if($index !== 1) {
        $parent = $roles[$index - 1];
      }
      $permission->addRole($role, $parent);
    }
    
    foreach($permissions as $role) {
      $permission->allow($role["role"], "guild", $role["action"]);
    }
    return $permission;
  }
}
?>