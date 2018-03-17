<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\DI;

use HeroesofAbenez\Chat\ChatCommandsProcessor,
    HeroesofAbenez\Chat\IChatCommand,
    Nette\Utils\Validators,
    HeroesofAbenez\Chat\ChatControl,
    HeroesofAbenez\Chat\InvalidChatControlFactoryException;

/**
 * ChatExtension
 *
 * @author Jakub Konečný
 */
class ChatExtension extends \Nette\DI\CompilerExtension {
  /** @internal */
  public const SERVICE_CHAT_COMMANDS_PROCESSOR = "commandsProcessor";
  
  protected $defaults = [
    "chats" => [],
  ];
  
  /**
   * @throws InvalidChatControlFactoryException
   */
  protected function validateFactory(string $interface): void {
    try {
      $rc = new \ReflectionClass($interface);
    } catch(\ReflectionException $e) {
      throw new InvalidChatControlFactoryException("Interface $interface not found.", 0, $e);
    }
    if(!$rc->isInterface()) {
      throw new InvalidChatControlFactoryException("$interface is not an interface.");
    }
    try {
      $rm = new \ReflectionMethod($interface, "create");
    } catch(\ReflectionException $e) {
      throw new InvalidChatControlFactoryException("Interface $interface does not contain method create.", 0, $e);
    }
    $returnType = $rm->getReturnType();
    if(is_null($returnType) OR !is_subclass_of($returnType->getName(), ChatControl::class)) {
      throw new InvalidChatControlFactoryException("Return type of $interface::create() is not a subtype of " . ChatControl::class . ".");
    }
  }
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws InvalidChatControlFactoryException
   */
  protected function getChats(): array {
    $chats = [];
    $config = $this->getConfig($this->defaults);
    Validators::assertField($config, "chats", "array");
    foreach($config["chats"] as $name => $interface) {
      $this->validateFactory($interface);
      $chats[$name] = $interface;
    }
    return $chats;
  }
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws InvalidChatControlFactoryException
   */
  public function loadConfiguration(): void {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix(static::SERVICE_CHAT_COMMANDS_PROCESSOR))
      ->setType(ChatCommandsProcessor::class);
    $chats = $this->getChats();
    foreach($chats as $name => $interface) {
      $builder->addDefinition($this->prefix($name))
        ->setImplement($interface);
    }
  }
  
  public function beforeCompile(): void {
    $builder = $this->getContainerBuilder();
    $processor = $builder->getDefinition($this->prefix(static::SERVICE_CHAT_COMMANDS_PROCESSOR));
    $chatCommands = $builder->findByType(IChatCommand::class);
    foreach($chatCommands as $command) {
      $processor->addSetup("addCommand", [$command]);
    }
  }
}
?>