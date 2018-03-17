<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\DI;

use HeroesofAbenez\Chat\ChatCommandsProcessor,
    HeroesofAbenez\Chat\IChatCommand,
    Nette\Utils\Validators,
    HeroesofAbenez\Chat\ChatControl,
    HeroesofAbenez\Chat\IChatMessageProcessor,
    Nette\DI\MissingServiceException,
    HeroesofAbenez\Chat\InvalidChatControlFactoryException,
    HeroesofAbenez\Chat\InvalidMessageProcessorException;

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
    "messageProcessors" => [
      self::SERVICE_CHAT_COMMANDS_PROCESSOR => ChatCommandsProcessor::class,
    ],
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
   * @throws InvalidMessageProcessorException
   */
  protected function getMessageProcessors(): array {
    $messageProcessors = [];
    $config = $this->getConfig($this->defaults);
    Validators::assertField($config, "messageProcessors", "array");
    foreach($config["messageProcessors"] as $name => $processor) {
      if(!class_exists($processor) OR !is_subclass_of($processor, IChatMessageProcessor::class)) {
        throw new InvalidMessageProcessorException("Invalid message processor $processor.");
      }
      $messageProcessors[$name] = $processor;
    }
    return $messageProcessors;
  }
  
  /**
   * @throws \Nette\Utils\AssertionException
   * @throws InvalidChatControlFactoryException
   * @throws InvalidMessageProcessorException
   */
  public function loadConfiguration(): void {
    $builder = $this->getContainerBuilder();
    $chats = $this->getChats();
    foreach($chats as $name => $interface) {
      $builder->addDefinition($this->prefix($name))
        ->setImplement($interface);
    }
    $messageProcessors = $this->getMessageProcessors();
    foreach($messageProcessors as $name => $processor) {
      $builder->addDefinition($this->prefix($name))
        ->setType($processor);
    }
  }
  
  protected function registerMessageProcessors(): void {
    $builder = $this->getContainerBuilder();
    $chats = $this->getChats();
    $messageProcessors = $this->getMessageProcessors();
    foreach($chats as $chat => $chatClass) {
      $chatService = $builder->getDefinition($this->prefix($chat));
      foreach($messageProcessors as $processor => $processorClass) {
        $processorService = $builder->getDefinition($this->prefix($processor));
        $chatService->addSetup("addMessageProcessor", [$processorService]);
      }
    }
  }
  
  protected function registerChatCommands(): void {
    $builder = $this->getContainerBuilder();
    try {
      $processor = $builder->getDefinition($this->prefix(static::SERVICE_CHAT_COMMANDS_PROCESSOR));
    } catch(MissingServiceException $e) {
      return;
    }
    $chatCommands = $builder->findByType(IChatCommand::class);
    foreach($chatCommands as $command) {
      $processor->addSetup("addCommand", [$command]);
    }
  }
  
  public function beforeCompile(): void {
    $this->registerMessageProcessors();
    $this->registerChatCommands();
  }
}
?>