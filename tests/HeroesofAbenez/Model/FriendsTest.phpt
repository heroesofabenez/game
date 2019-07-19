<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

final class FriendsTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var Friends */
  protected $model;
  
  public function setUp() {
    $this->model = $this->getService(Friends::class);
  }
  
  public function testIsFriendsWith() {
    Assert::true($this->model->isFriendsWith(1));
    Assert::true($this->model->isFriendsWith(2));
    Assert::true($this->model->isFriendsWith(3));
    Assert::false($this->model->isFriendsWith(4));
  }

  public function testBefriend() {
    Assert::exception(function() {
      $this->model->befriend(1);
    }, AlreadyFriendsException::class);
    $this->model->befriend(4);
    /** @var ORM $orm */
    $orm = $this->getService(ORM::class);
    $request = $orm->requests->getBy([
      "from" => 1,
      "to" => 4,
      "type" => \HeroesofAbenez\Orm\Request::TYPE_FRIENDSHIP,
      "status" => \HeroesofAbenez\Orm\Request::STATUS_NEW,
    ]);
    Assert::type(\HeroesofAbenez\Orm\Request::class, $request);
    Assert::exception(function() {
      $this->model->befriend(4);
    }, FriendshipRequestAlreadySentException::class);
    $orm->requests->removeAndFlush($request);
  }
}

$test = new FriendsTest();
$test->run();
?>