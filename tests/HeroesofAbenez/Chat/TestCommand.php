<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

final class TestCommand extends ChatCommand {
  protected $name = ChatCommandsProcessorTest::COMMAND_NAME;
  
  public function execute(): string {
    return "passed";
  }
}
?>