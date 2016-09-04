<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\ChatCommand;

class TestCommand extends ChatCommand {
  function __construct() {
    \Tracy\Debugger::barDump(ChatCommandsProcessorTest::COMMAND_NAME);
    parent::__construct(ChatCommandsProcessorTest::COMMAND_NAME);
  }
  
  /**
    \Tracy\Debugger::barDump($this->name);
   * @return string
   */
  function execute() {
    return "passed";
  }
}

class Test2Command extends ChatCommand {
  const NAME = "test2";
  
  function __construct() {
    parent::__construct(self::NAME);
  }
  
  /**
   * @return string
   */
  function execute() {
    return "test";
  }
}

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
  function testParse() {
    Assert::same("passed", $this->model->parse(self::TEXT));
    Assert::false($this->model->parse("anagfdffd"));
    Assert::false($this->model->parse("/anagfdffd"));
  }
}
?>