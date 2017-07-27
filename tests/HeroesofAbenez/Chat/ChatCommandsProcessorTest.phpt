<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

class ChatCommandsProcessorTest extends \Tester\TestCase {
  const COMMAND_NAME = "test1";
  const TEXT = "/" . self::COMMAND_NAME;
  
  /** @var ChatCommandsProcessor */
  protected $model = NULL;
  
  use \Testbench\TCompiledContainer;
  
  /**
   * @return void
   */
  public function setUp() {
    if(is_null($this->model)) {
      $this->model = $this->getService(ChatCommandsProcessor::class);
      $this->model->addCommand(new TestCommand);
    }
  }
  
  /**
   * @return void
   */
  public function testCommandTime() {
    $time = $this->model->parse("/time");
    Assert::contains("Current time is ", $time);
    Assert::contains(date("Y-m-d "), $time);
  }
  
  /**
   * @return void
   */
  public function testCommandLocation() {
    $result = $this->model->parse("/location");
    Assert::contains("You're currently in ", $result);
  }
  
  /**
   * @return void
   */
  public function testAddCommand() {
    $model = clone $this->model;
    $model->addCommand(new Test2Command);
    Assert::same("test", $model->parse("/" . Test2Command::NAME));
  }
  
  /**
   * @return void
   */
  public function testAddAlias() {
    $model = clone $this->model;
    $model->addAlias(self::COMMAND_NAME, "test");
    Assert::same("passed", $model->parse("/test"));
  }
  
  /**
   * @return void
   */
  public function testExtractCommand() {
    Assert::same("", $this->model->extractCommand("anagfdffd"));
    Assert::same("", $this->model->extractCommand("/anagfdffd"));
    Assert::same(self::COMMAND_NAME, $this->model->extractCommand(self::TEXT));
  }
  
  /**
   * @return string[]
   */
  public function getTexts(): array {
    return [
      ["anagfdffd", "/anagfdffd", ]
    ];
  }
  
  /**
   * @param string $text
   * @dataProvider getTexts
   * @return void
   */
  public function testExtractParametersNothing(string $text) {
    $result = $this->model->extractParameters($text);
    Assert::type("array", $result);
    Assert::count(0, $result);
  }
  
  /**
   * @return void
   */
  public function testExtractParameters() {
    $result = $this->model->extractParameters("/test abc 123");
    Assert::type("array", $result);
    Assert::count(2, $result);
  }
  
  /**
   * @return void
   */
  public function testHasCommand() {
    Assert::false($this->model->hasCommand("anagfdffd"));
    Assert::true($this->model->hasCommand(self::COMMAND_NAME));
  }
  
  /**
   * @return void
   */
  public function testParse() {
    $model = clone $this->model;
    $model->addCommand(new Test2Command);
    Assert::same("passed", $this->model->parse(self::TEXT));
    Assert::null($model->parse("anagfdffd"));
    Assert::null($model->parse("/anagfdffd"));
    Assert::same("test12", $model->parse("/test2 1 2"));
  }
}

$test = new ChatCommandsProcessorTest;
$test->run();
?>