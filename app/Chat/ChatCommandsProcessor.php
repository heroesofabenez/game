<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * ChatCommandsProcessor
 *
 * @author Jakub Konečný
 */
class ChatCommandsProcessor {
  use \Nette\SmartObject;
  
  /** @var ChatCommand[] */
  protected $commands = [];
  
  /**
   * Add new command
   *
   * @throws CommandNameAlreadyUsedException
   */
  public function addCommand(ChatCommand $command): void {
    if($this->hasCommand($command->name)) {
      throw new CommandNameAlreadyUsedException("Command $command->name is already defined.");
    }
    $this->commands[] = $command;
  }
  
  /**
   * Add an alias for already defined command
   *
   * @throws CommandNotFoundException
   * @throws CommandNameAlreadyUsedException
   */
  public function addAlias(string $oldName, string $newName): void {
    try {
      $command = $this->getCommand($oldName);
    } catch(CommandNotFoundException $e) {
      throw $e;
    }
    $new = clone $command;
    $new->name = $newName;
    try {
      $this->addCommand($new);
    } catch(CommandNameAlreadyUsedException $e) {
      throw $e;
    }
  }
  
  /**
   * Extract command from text
   */
  public function extractCommand(string $text): string {
    if(substr($text, 0, 1) != "/") {
      return "";
    }
    if(!strpos($text, " ")) {
      $command = substr($text, 1);
    } else {
      $parts = explode(" ", substr($text, 1));
      $command = $parts[0];
    }
    if($this->hasCommand($command)) {
      return $command;
    }
    return "";
  }
  
  
  /**
   * Extract parameters from text
   */
  public function extractParameters(string $text): array {
    if(substr($text, 0, 1) != "/" OR !strpos($text, " ")) {
      return [];
    }
    $params = explode(" ", $text);
    unset($params[0]);
    return $params;
  }
  
  /**
   * Check whether a command is defined
   */
  public function hasCommand(string $name): bool {
    foreach($this->commands as $command) {
      if($command->name === $name) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Get command's definition
   *
   * @throws CommandNotFoundException
   */
  public function getCommand(string $name): ChatCommand {
    foreach($this->commands as $command) {
      if($command->name === $name) {
        return $command;
      }
    }
    throw new CommandNotFoundException("Command $name is not defined.");
  }
  
  /**
   * @return string|null Result of the command/null when text text contains no (defined) command
   */
  public function parse(string $text): ?string {
    $commandName = $this->extractCommand($text);
    if($commandName === "") {
      return NULL;
    }
    $command = $this->getCommand($commandName);
    $params = $this->extractParameters($text);
    return call_user_func_array([$command, "execute"], $params);
  }
}
?>