<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model\DI;

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert,
    HeroesofAbenez\Model\IUserToCharacterMapper,
    HeroesofAbenez\Model\TestingUserToCharacterMapper,
    HeroesofAbenez\Model\DevelopmentUserToCharacterMapper;

final class HOAExtensionTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  public function testUserToCharacterMapper() {
    Assert::type(TestingUserToCharacterMapper::class, $this->getService(IUserToCharacterMapper::class));
    $this->refreshContainer([
      "hoa" => [
        "userToCharacterMapper" => DevelopmentUserToCharacterMapper::class
      ]
    ]);
    Assert::type(DevelopmentUserToCharacterMapper::class, $this->getService(IUserToCharacterMapper::class));
    Assert::exception(function() {
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
?>