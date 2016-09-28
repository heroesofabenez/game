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
  /** @var  \Nette\Database\Context */
  protected $db;
  
  /**
   * CombatHelperTest constructor.
   * @param \HeroesofAbenez\Model\CombatHelper $model
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   */
  public function __construct(\HeroesofAbenez\Model\CombatHelper $model, \Nette\Security\User $user, \Nette\Database\Context $db) {
    $this->model = $model;
    $this->user = $user;
    $this->db = $db;
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
    $day = date("d.m.Y");
    $row = $this->db->table("arena_fights_count")->where("character=? AND day=?", [$this->user->id, $day]);
    $row->delete();
  }
}
?>