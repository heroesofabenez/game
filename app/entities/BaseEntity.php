<?php
namespace HeroesofAbenez\Entities;

/**
 * Parent of all entities
 * Provides read access to all properties, allows working with entity as array
 *
 * @author Jakub Konečný
 */
abstract class BaseEntity implements \ArrayAccess {
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
  
  /**
   * Do not allow write access to properties by default
   * 
   * @param string $name
   * @param string $value
   * @throws \Nette\MemberAccessException
   */
  function __set($name, $value) {
    $class = get_class($this);
    throw new \Nette\MemberAccessException("Cannot write to property $class::\$$name.");
  }
  
  /**
   * Converts entity into array
   * 
   * @return array
   */
  function __toArray() {
    $return = array();
    foreach($this as $key => $value) {
      $return[$key] = $value;
    }
    return $return;
  }
  
  /**
   * @param mixed $offset
   * @return mixed
   * @throws \Nette\MemberAccessException
   */
  function offsetGet($offset) {
    $class = get_class($this);
    $rc = new \ReflectionClass($class);
    $method = "get" . ucfirst($offset);
    if($rc->hasMethod($method)) return $this->$method();
    elseif($rc->hasProperty($offset)) return $this->$offset;
    throw new \Nette\MemberAccessException("Cannot read property $class::\$$offset.");
  }
  
  /**
   * @param mixed $offset
   * @param mixed $value
   * @throws \Nette\MemberAccessException
   * @return void
   */
  function offsetSet($offset, $value) {
    $class = get_class($this);
    throw new \Nette\MemberAccessException("Cannot write to property $class::\$$offset.");
  }
  
  /**
   * @param mixed $offset
   * @return bool
   */
  function offsetExists($offset) {
    return isset($this->$offset);
  }
  
  /**
   * @param mixed $offset
   * @throws \Nette\MemberAccessException
   * @return void
   */
  function offsetUnset($offset) {
    $class = get_class($this);
    throw new \Nette\MemberAccessException("Cannot unset property $class::\$$offset.");
  }
}
?>