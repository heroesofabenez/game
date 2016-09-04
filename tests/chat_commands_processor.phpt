<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

class ChatCommandsProcessorTest extends MT\TestCase {
  const COMMAND_NAME = "test1";
  const TEXT = "/" . self::COMMAND_NAME;
  
  /** @var \HeroesofAbenez\Model\ChatCommandsProcessor */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\ChatCommandsProcessor $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function startUp() {
    $this->model->addCommand(self::COMMAND_NAME, function() {
      return "passed"; 
    });
  }
  
  /**
   * @return void
   */
  function testCommandTime() {
    $time = $this->model->executeCommand("time");
    Assert::contains("Current time is ", $time);
    Assert::contains(date("Y-m-d "), $time);
  }
  
  /**
   * @return void
   */
  function testCommandLocation() {
    $result = $this->model->executeCommand("location");
    Assert::contains("You're currently in ", $result);
  }
  
  /**
   * @return void
   */
  function testAddCommand() {
    $this->model->addCommand("test2", function() {
      return "test"; 
    }, ["testParam" => "testValue"]);
    $params = $this->model->getCommand("test2")->parameters;
    Assert::type(\Nette\Security\User::class, $params["user"]);
    Assert::type("string", $params["testParam"]);
    Assert::same("testValue", $params["testParam"]);
  }
  
  /**
   * @return void
   */
  function testExtractCommand() {
    Assert::same("", $this->model->extractCommand("anagfdffd"));
    Assert::same("", $this->model->extractCommand("/anagfdffd"));
    Assert::same(self::COMMAND_NAME, $this->model->extractCommand(self::TEXT));
  }
  
  /**
   * @return void
   */
  function testHasCommand() {
    Assert::false($this->model->hasCommand("anagfdffd"));
    Assert::true($this->model->hasCommand(self::COMMAND_NAME));
  }
  
  /**
   * @return void
   */
  function testExecuteCommand() {
    $command = $this->model->extractCommand(self::TEXT);
    Assert::same("passed", $this->model->executeCommand($command));
  }
}
?>