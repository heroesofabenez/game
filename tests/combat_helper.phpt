<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Character;

class CombatHelperTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\CombatHelper */
  protected $model;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\HeroesofAbenez\Model\CombatHelper $model, \Nette\Security\User $user) {
    $this->model = $model;
    $this->user = $user;
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
    \Tracy\Debugger::barDump($result);
    Assert::same(1, $result);
    $this->model->bumpNumberOfTodayArenaFights($this->user->id);
    $result = $this->model->getNumberOfTodayArenaFights($this->user->id);
    \Tracy\Debugger::barDump($result);
    Assert::same(2, $result);
  }
}
?>