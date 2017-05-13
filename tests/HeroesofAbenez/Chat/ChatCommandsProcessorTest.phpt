<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Chat\ChatCommand;

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

class ChatCommandsProcessorTest extends MT\TestCase {
  const COMMAND_NAME = "test1";
  const TEXT = "/" . self::COMMAND_NAME;
  
  /** @var ChatCommandsProcessor */
  protected $model;
  
  function __construct(ChatCommandsProcessor $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function startUp() {
    $this->model->addCommand(new TestCommand);
  }
  
  /**
   * @return void
   */
  function testCommandTime() {
    $time = $this->model->parse("/time");
    Assert::contains("Current time is ", $time);
    Assert::contains(date("Y-m-d "), $time);
  }
  
  /**
   * @return void
   */
  function testCommandLocation() {
    $result = $this->model->parse("/location");
    Assert::contains("You're currently in ", $result);
  }
  
  /**
   * @return void
   */
  function testAddCommand() {
    $this->model->addCommand(new Test2Command);
    Assert::same("test", $this->model->parse("/" . Test2Command::NAME));
  }
  
  /**
   * @return void
   */
  function testAddAlias() {
    $this->model->addAlias(self::COMMAND_NAME, "test");
    Assert::same("passed", $this->model->parse("/test"));
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
   * @param string $text
   * @data("anagfdffd", "/anagfdffd")
   * @return void
   */
  function testExtractParametersNothing(string $text) {
    $result = $this->model->extractParameters($text);
    Assert::type("array", $result);
    Assert::count(0, $result);
  }
  
  /**
   * @return void
   */
  function testExtractParameters() {
    $result = $this->model->extractParameters("/test abc 123");
    Assert::type("array", $result);
    Assert::count(2, $result);
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
  function testParse() {
    Assert::same("passed", $this->model->parse(self::TEXT));
    Assert::null($this->model->parse("anagfdffd"));
    Assert::null($this->model->parse("/anagfdffd"));
    Assert::same("test12", $this->model->parse("/test2 1 2"));
  }
}
?>