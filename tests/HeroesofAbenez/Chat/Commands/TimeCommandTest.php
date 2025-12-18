<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class TimeCommandTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    private TimeCommand $command;

    protected function setUp(): void
    {
        $this->command = $this->getService(TimeCommand::class); // @phpstan-ignore assign.propertyType
    }

    public function testExecute(): void
    {
        $time = $this->command->execute();
        Assert::contains("Current time is ", $time);
        Assert::contains(date("Y-m-d "), $time);
    }
}

$test = new TimeCommandTest();
$test->run();
