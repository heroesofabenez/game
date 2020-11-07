<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 * @testCase
 */
final class DevelopmentUserToCharacterMapperTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  public function testGetRealId() {
    $this->refreshContainer([
      "hoa" => [
        "userToCharacterMapper" => DevelopmentUserToCharacterMapper::class
      ]
    ]);
    /** @var DevelopmentUserToCharacterMapper $mapper */
    $mapper = $this->getService(DevelopmentUserToCharacterMapper::class);
    Assert::same(1, $mapper->getRealId());
  }
}

$test = new DevelopmentUserToCharacterMapperTest();
$test->run();
?>