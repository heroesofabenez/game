<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

class Test2Command extends ChatCommand {
  const NAME = "test2";
  
  /**
   * @return string
   */
  function execute() : string {
    $args = func_get_args();
    $text = "test";
    foreach($args as $arg) {
      $text .= $arg;
    }
    return $text;
  }
}
?>