<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Orm\Model as ORM;

class CombatHelperTest extends MT\TestCase {
  /** @var CombatHelper */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  
  function __construct(CombatHelper $model, \Nette\Security\User $user, ORM $orm) {
    $this->model = $model;
    $this->user = $user;
    $this->orm = $orm;
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetInitiativeFormula(int $id) {
    $result = $this->model->getInitiativeFormula($id);
    Assert::type("string", $result);
    Assert::true((strlen($result) > 1));
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetPlayer(int $id) {
    $player = $this->model->getPlayer($id);
    Assert::type(Character::class, $player);
    Assert::true(count($player->pets));
    Assert::true(count($player->equipment));
    Assert::true(count($player->skills));
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetArenaNpc(int $id) {
    $player = $this->model->getArenaNpc($id);
    Assert::type(Character::class, $player);
    Assert::false(count($player->pets));
    Assert::true(count($player->skills));
  }
  
  /**
   * @return void
   */
  function testGetNumberOfTodayArenaFights() {
    $actual = $this->model->getNumberOfTodayArenaFights($this->user->id);
    Assert::type("int", $actual);
    Assert::same(0, $actual);
  }
  
  /**
   * @return void
   */
  function testBumpNumberOfTodayArenaFights() {
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
  function shutDown() {
    $record = $this->orm->arenaFightsCount->getByCharacterAndDay($this->user->id, date("d.m.Y"));
    $this->orm->arenaFightsCount->removeAndFlush($record);
  }
}
?>