<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class LocationCommandTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;

  protected LocationCommand $command;
  
  protected function setUp(): void {
    $this->command = $this->getService(LocationCommand::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testExecute(): void {
    $result = $this->command->execute();
    Assert::contains("You're currently in ", $result);
  }
}

$test = new LocationCommandTest();
$test->run();
?>