<?php
namespace HeroesofAbenez\Entities;

/**
 * Chat Command
 *
 * @author Jakub Konečný
 */
class ChatCommand extends BaseEntity {
  /** @var string */
  protected $name;
  /** @var callable */
  protected $callback;
  /** @var array */
  protected $parameters;
  
  function __construct($name, callable $callback, array $parameters = []) {
    $this->name = $name;
    $this->callback = $callback;
    $this->parameters = $parameters;
  }
  
  /**
   * @return string
   */
  function execute() {
    return call_user_func_array($this->callback, [$this->parameters]);
  }
}
?>