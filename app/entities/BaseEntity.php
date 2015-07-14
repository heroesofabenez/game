<?php
namespace HeroesofAbenez\Entities;

/**
 * Parent of all entities. Provides read access to all properties
 *
 * @author Jakub Konečný
 */
abstract class BaseEntity {
  /**
   * Firstly try to call method getProperty, then directly get property
   * and if both fails, throw an exception
   * 
   * @param string $name Property's name
   * @return mixed Property's value
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