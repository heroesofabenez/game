<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\ChatCommand;

/**
 * ChatCommandsProcessor
 *
 * @author Jakub Konečný
 */
class ChatCommandsProcessor {
  use \Nette\SmartObject;
  
  /** @var ChatCommand[] */
  protected $commands = [];
  /** @var array */
  protected $parameters = [];
  
  function __construct(\Nette\Security\User $user, \Nette\Database\Context $db, \Kdyby\Translation\Translator $translator) {
    $this->parameters["user"] = $user;
    $this->parameters["db"] = $db;
    $this->parameters["translator"] = $translator;
    $this->addDefaultCommands();
  }
  
  /**
   * @return void
   */
  protected function addDefaultCommands() {
    $this->addCommand("time", function($params) {
      $time = date("Y-m-d H:i:s");
      return $params["translator"]->translate("messages.chat.currentTime", ["time" => $time]); 
    });
    $this->addCommand("location", function($params) {
      $stageId = $params["user"]->identity->stage;
      $stage = $params["db"]->table("quest_stages")->get($stageId);
      $area = $params["db"]->table("quest_areas")->get($stage->area);
      return $params["translator"]->translate("messages.chat.currentLocation", ["stageName" => $stage->name, "areaName" => $area->name]);
    });
  }
  
  /**
   * Add new  command
   *
   * @param string $name
   * @param callable $callback
   * @param array $parameters
   * @return void
   * @throws CommandNameAlreadyUsedException
   */
  function addCommand($name, callable $callback, array $parameters = []) {
    if($this->hasCommand($name)) throw new CommandNameAlreadyUsedException("Command $name is already defined.");
    $this->commands[] = new ChatCommand($name, $callback, array_merge($this->parameters, $parameters));
  }
  
  /**
   * Extract command from text
   *
   * @param string $text
   * @return string
   */
  function extractCommand($text) {
    if(substr($text, 0, 1) === "/" AND $this->hasCommand(substr($text, 1))) {
      return substr($text, 1);
    } else {
      return "";
    }
  }
  
  /**
   * Check whetever a command is defined
   *
   * @param string $name
   * @return bool
   */
  function hasCommand($name) {
    foreach($this->commands as $command) {
      if($command->name === $name) return true;
    }
    return false;
  }
  
  /**
   * Get command's definition
   *
   * @param string $name
   * @return ChatCommand
   * @throws CommandNotFoundException
   */
  function getCommand($name) {
    foreach($this->commands as $command) {
      if($command->name === $name) return $command;
    }
    throw new CommandNotFoundException;
  }
  
  /**
   * Execute a command
   *
   * @param string $name
   * @return string
   * @throws CommandNotFoundException
   */
  function executeCommand($name) {
    try {
      $command = $this->getCommand($name);
    } catch(CommandNotFoundException $e) {
      throw $e;
    }
    return $command->execute();
  }
}
?>