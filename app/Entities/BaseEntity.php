<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Parent of all entities
 * Provides read access to all properties
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
  function __get(string $name) {
    $class = get_class($this);
    $rc = new \ReflectionClass($class);
    $method = "get" . ucfirst($name);
    if($rc->hasMethod($method)) {
      return $this->$method();
    } elseif($rc->hasProperty($name)) {
      return $this->$name;
    }
    throw new \Nette\MemberAccessException("Cannot read property $class::\$$name.");
  }
  
  /**
   * Do not allow write access to properties by default
   * 
   * @param string $name
   * @param string $value
   * @throws \Nette\MemberAccessException
   */
  function __set(string $name, string $value) {
    $class = get_class($this);
    $rc = new \ReflectionClass($class);
    $method = "set" . ucfirst($name);
    if($rc->hasMethod($method)) {
      call_user_func([$this, $method], $value);
    } else {
      throw new \Nette\MemberAccessException("Cannot write to property $class::\$$name.");
    }
  }
}
?>