<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

final class ConstantsTest extends \Tester\TestCase {
  public const ABC_A = "a";
  public const ABC_B = "b";
  
  public function testGetConstantsValues() {
    $result = Constants::getConstantsValues(static::class, "ABC_");
    Assert::type("array", $result);
    Assert::count(2, $result);
    foreach($result as $item) {
      Assert::type("string", $item);
    }
    Assert::same("a", $result[0]);
    Assert::same("b", $result[1]);
  }
}

$test = new ConstantsTest();
$test->run();
?>