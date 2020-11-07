<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 * @testCase
 */
final class TimeCommandTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var TimeCommand */
  protected $command;
  
  protected function setUp() {
    $this->command = $this->getService(TimeCommand::class);
  }
  
  public function testExecute() {
    $time = $this->command->execute();
    Assert::contains("Current time is ", $time);
    Assert::contains(date("Y-m-d "), $time);
  }
}

$test = new TimeCommandTest();
$test->run();
?>