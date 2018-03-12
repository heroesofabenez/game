<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

final class NumbersTest extends \Tester\TestCase {
  public function testRange() {
    Assert::same(0, Numbers::range(-10, 0, 50));
    Assert::same(50, Numbers::range(100, 0, 50));
    Assert::same(25, Numbers::range(25, 0, 50));
  }
}

$test = new NumbersTest();
$test->run();
?>