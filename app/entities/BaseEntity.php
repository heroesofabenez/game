<?php
namespace HeroesofAbenez\Entities;

/**
 * Parent of all entities. Provides read access to all properties
 *
 * @author Jakub Konečný
 */
class BaseEntity {
  /**
   * @param string $name
   * @return mixed
   * @throws \Nette\MemberAccessException
   */
  function __get($name) {
    $class = get_class($this);
    $rc = new \ReflectionClass($class);
    $method = "get" . ucfirst($name);
    if($rc->hasMethod($method)) return $this->$method();
    elseif($rc->hasProperty($name)) return $this->$name;
    throw new \Nette\MemberAccessException("Cannot read property $class::\$$name.");
  }
}
?>