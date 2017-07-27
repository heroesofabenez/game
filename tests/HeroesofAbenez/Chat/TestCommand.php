<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

class TestCommand extends ChatCommand {
  public function __construct() {
    $this->name = ChatCommandsProcessorTest::COMMAND_NAME;
  }
  
  /**
   * @return string
   */
  public function execute(): string {
    return "passed";
  }
}
?>