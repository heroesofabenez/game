<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

class TestCommand extends ChatCommand {
  function __construct() {
    $this->name = ChatCommandsProcessorTest::COMMAND_NAME;
  }
  
  /**
   * @return string
   */
  function execute(): string {
    return "passed";
  }
}
?>