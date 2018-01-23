<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

final class Test2Command extends ChatCommand {
  const NAME = "test2";
  
  public function execute() : string {
    $args = func_get_args();
    $text = "test";
    foreach($args as $arg) {
      $text .= $arg;
    }
    return $text;
  }
}
?>