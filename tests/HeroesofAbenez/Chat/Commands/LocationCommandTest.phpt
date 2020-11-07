<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 * @testCase
 */
final class LocationCommandTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var LocationCommand */
  protected $command;
  
  protected function setUp() {
    $this->command = $this->getService(LocationCommand::class);
  }
  
  public function testExecute() {
    $result = $this->command->execute();
    Assert::contains("You're currently in ", $result);
  }
}

$test = new LocationCommandTest();
$test->run();
?>