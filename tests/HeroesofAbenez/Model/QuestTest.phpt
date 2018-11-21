<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Quest as QuestEntity;

require __DIR__ . "/../../bootstrap.php";

final class QuestTest extends \Tester\TestCase {
  /** @var Quest */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Quest::class);
  }
  
  public function testListOfQuests() {
    $quests = $this->model->listOfQuests();
    Assert::type("array", $quests);
    Assert::type(QuestEntity::class, $quests[1]);
    $quests = $this->model->listOfQuests(1);
    Assert::type("array", $quests);
    Assert::type(QuestEntity::class, $quests[1]);
    Assert::same(1, $quests[1]->npcStart->id);
  }
  
  public function testView() {
    $quest = $this->model->view(1);
    Assert::type(QuestEntity::class, $quest);
    Assert::null($this->model->view(5000));
  }
  
  public function testStatus() {
    $result = $this->model->status(1);
    Assert::type("integer", $result);
    Assert::same(1, $result);
  }
  
  public function testIsFinished() {
    $result = $this->model->isFinished(1);
    Assert::type("bool", $result);
    Assert::false($result);
  }

  public function testIsCompleted() {
    /** @var QuestEntity $quest */
    $quest = $this->model->view(1);
    $oldCostMoney = $quest->costMoney;
    $oldNeededItem = $quest->neededItem;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var \HeroesofAbenez\Orm\Character $user */
    $user = $orm->characters->getById(1);
    $quest->costMoney = $user->money + 1;
    Assert::false($this->model->isCompleted($quest));
    $quest->costMoney = $oldCostMoney;
    Assert::false($this->model->isCompleted($quest));
    $quest->neededItem = null;
    Assert::true($this->model->isCompleted($quest));
    $quest->neededItem = $oldNeededItem;
    $orm->quests->persistAndFlush($quest);
  }

  public function testFinish() {
    Assert::exception(function() {
      $this->model->finish(5000, 1);
    }, QuestNotFoundException::class);
    Assert::exception(function() {
      $this->model->finish(1, 2);
    }, CannotFinishQuestHereException::class);
    Assert::exception(function() {
      $this->model->finish(1, 1);
    }, QuestNotFinishedException::class);
  }

  public function testIsAvailable() {
    /** @var QuestEntity $quest */
    $quest = $this->model->view(1);
    $oldNeededLevel = $quest->neededLevel;
    $oldNeededQuest = $quest->neededQuest;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var \HeroesofAbenez\Orm\Character $user */
    $user = $orm->characters->getById(1);
    $quest->neededLevel = $user->level + 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->neededLevel = $oldNeededLevel;
    $quest->neededQuest = 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->neededQuest = $oldNeededQuest;
    Assert::true($this->model->isAvailable($quest));
  }

  public function testAccept() {
    Assert::exception(function() {
      $this->model->accept(5000, 1);
    }, QuestNotFoundException::class);
    Assert::exception(function() {
      $this->model->accept(1, 1);
    }, QuestAlreadyStartedException::class);
  }
}

$test = new QuestTest();
$test->run();
?>