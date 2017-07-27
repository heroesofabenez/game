<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Orm\Model as ORM;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class CombatHelperTest extends \Tester\TestCase {
  /** @var CombatHelper */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(CombatHelper::class);
    $this->user = $this->getService(\Nette\Security\User::class);
    $this->orm = $this->getService(ORM::class);
  }
  
  /**
   * @return void
   */
  public function testGetInitiativeFormula() {
    $result = $this->model->getInitiativeFormula(1);
    Assert::type("string", $result);
    Assert::true((strlen($result) > 1));
  }
  
  /**
   * @return void
   */
  public function testGetPlayer() {
    $player = $this->model->getPlayer(1);
    Assert::type(Character::class, $player);
    Assert::count(1, $player->pets);
    Assert::count(0, $player->equipment);
    Assert::count(1, $player->skills);
  }
  
  /**
   * @return void
   */
  public function testGetArenaNpc() {
    $player = $this->model->getArenaNpc(1);
    Assert::type(Character::class, $player);
    Assert::count(0, $player->pets);
    Assert::count(1, $player->skills);
  }
  
  /**
   * @return void
   */
  public function testGetNumberOfTodayArenaFights() {
    $actual = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::type("int", $actual);
    Assert::same(0, $actual);
  }
  
  /**
   * @return void
   */
  public function testBumpNumberOfTodayArenaFights() {
    $this->model->bumpNumberOfTodayArenaFights($this->user->id);
    $result = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::same(1, $result);
    $this->model->bumpNumberOfTodayArenaFights($this->user->id);
    $result = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::same(2, $result);
  }
  
  /**
   * @return void
   */
  public function shutDown() {
    $record = $this->orm->arenaFightsCount->getByCharacterAndDay($this->user->id, date("d.m.Y"));
    $this->orm->arenaFightsCount->removeAndFlush($record);
  }
}

$test = new CombatHelperTest;
$test->run();
?>