<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 * @testCase
 */
final class KarmaTest extends \Tester\TestCase {
  public function testGetKarmas() {
    $result = Karma::getKarmas();
    Assert::type("array", $result);
    Assert::count(3, $result);
    foreach($result as $value) {
      Assert::type("string", $value);
    }
  }
  
  public function testIsSame() {
    Assert::true(Karma::isSame(Karma::KARMA_WHITE, Karma::KARMA_WHITE));
    Assert::true(Karma::isSame(Karma::KARMA_NEUTRAL, Karma::KARMA_NEUTRAL));
    Assert::true(Karma::isSame(Karma::KARMA_DARK, Karma::KARMA_DARK));
    Assert::false(Karma::isSame(Karma::KARMA_WHITE, Karma::KARMA_NEUTRAL));
    Assert::false(Karma::isSame(Karma::KARMA_WHITE, Karma::KARMA_DARK));
    Assert::false(Karma::isSame(Karma::KARMA_NEUTRAL, Karma::KARMA_WHITE));
    Assert::false(Karma::isSame(Karma::KARMA_NEUTRAL, Karma::KARMA_DARK));
    Assert::false(Karma::isSame(Karma::KARMA_DARK, Karma::KARMA_WHITE));
    Assert::false(Karma::isSame(Karma::KARMA_DARK, Karma::KARMA_NEUTRAL));
    Assert::exception(function() {
      Karma::isSame("a", Karma::KARMA_WHITE);
    }, \OutOfBoundsException::class);
    Assert::exception(function() {
      Karma::isSame(Karma::KARMA_WHITE, "a");
    }, \OutOfBoundsException::class);
  }
  
  public function testGetOpposite() {
    Assert::null(Karma::getOpposite(Karma::KARMA_NEUTRAL));
    Assert::same(Karma::KARMA_DARK, Karma::getOpposite(Karma::KARMA_WHITE));
    Assert::same(Karma::KARMA_WHITE, Karma::getOpposite(Karma::KARMA_DARK));
    Assert::exception(function() {
      Karma::getOpposite("a");
    }, \OutOfBoundsException::class);
  }
  
  public function testIsOpposite() {
    Assert::true(Karma::isOpposite(Karma::KARMA_WHITE, Karma::KARMA_DARK));
    Assert::true(Karma::isOpposite(Karma::KARMA_DARK, Karma::KARMA_WHITE));
    Assert::false(Karma::isOpposite(Karma::KARMA_WHITE, Karma::KARMA_NEUTRAL));
    Assert::false(Karma::isOpposite(Karma::KARMA_WHITE, Karma::KARMA_WHITE));
    Assert::false(Karma::isOpposite(Karma::KARMA_NEUTRAL, Karma::KARMA_WHITE));
    Assert::false(Karma::isOpposite(Karma::KARMA_NEUTRAL, Karma::KARMA_DARK));
    Assert::false(Karma::isOpposite(Karma::KARMA_DARK, Karma::KARMA_DARK));
    Assert::false(Karma::isOpposite(Karma::KARMA_DARK, Karma::KARMA_NEUTRAL));
    Assert::exception(function() {
      Karma::isOpposite("a", Karma::KARMA_WHITE);
    }, \OutOfBoundsException::class);
    Assert::exception(function() {
      Karma::isOpposite(Karma::KARMA_WHITE, "a");
    }, \OutOfBoundsException::class);
  }
  
  public function testGetPredominant() {
    $whiteKarma = $darkKarma = 0;
    Assert::same(Karma::KARMA_NEUTRAL, Karma::getPredominant($whiteKarma, $darkKarma));
    $whiteKarma = 5;
    Assert::same(Karma::KARMA_NEUTRAL, Karma::getPredominant($whiteKarma, $darkKarma));
    $whiteKarma = 6;
    Assert::same(Karma::KARMA_WHITE, Karma::getPredominant($whiteKarma, $darkKarma));
    $darkKarma = 11;
    Assert::same(Karma::KARMA_NEUTRAL, Karma::getPredominant($whiteKarma, $darkKarma));
    $darkKarma = 12;
    Assert::same(Karma::KARMA_DARK, Karma::getPredominant($whiteKarma, $darkKarma));
  }
}

$test = new KarmaTest();
$test->run();
?>