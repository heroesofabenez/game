<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model\DI;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;
use HeroesofAbenez\Model\IUserToCharacterMapper;
use HeroesofAbenez\Model\TestingUserToCharacterMapper;
use HeroesofAbenez\Model\DevelopmentUserToCharacterMapper;

/**
 * @author Jakub Konečný
 */
final class HOAExtensionTest extends \Tester\TestCase
{
    use \Testbench\TCompiledContainer;

    public function testUserToCharacterMapper(): void
    {
        Assert::type(TestingUserToCharacterMapper::class, $this->getService(IUserToCharacterMapper::class));
        $this->refreshContainer([
            "hoa" => [
                "userToCharacterMapper" => DevelopmentUserToCharacterMapper::class
            ]
        ]);
        Assert::type(DevelopmentUserToCharacterMapper::class, $this->getService(IUserToCharacterMapper::class));
        Assert::exception(function () {
            $this->refreshContainer([
                "hoa" => [
                    "userToCharacterMapper" => \stdClass::class
                ]
            ]);
        }, \RuntimeException::class);
    }
}

$test = new HOAExtensionTest();
$test->run();
