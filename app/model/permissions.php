<?php
namespace HeroesofAbenez;

/**
 * Permissions Model
 *
 * @author Jakub Konečný
 */
class Permissions extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
  }
  
  /**
   * Get roles (from db or cache)
   * 
   * @return array
   */
  function getRoles() {
    $roles = $this->cache->load("roles");
    if($roles === NULL) {
      $rolesRows = $this->db->table("guild_ranks")->order("id");
      foreach($rolesRows as $i => $row) {
        $roles[$i] = array("id" => $row->id, "name" => $row->name);
      }
      $this->cache->save("roles", $roles);
    }
    return $roles;
  }
  
  /**
   * Get permissions (from db or cache)
   * 
   * @return array
   */
  function getPermissions() {
    $roles = $this->getRoles();
    $permissions = $this->cache->load("permissions");
    if($permissions === NULL) {
      $privileges = $this->db->table("guild_privileges");
      foreach($privileges as $row) {
        $role = $roles[$row->rank];
        $permissions[$row->id] = array("role" => $role["name"], "action" => $row->action);
      }
      $this->cache->save("permissions", $permissions);
    }
    return $permissions;
  }
}
?>