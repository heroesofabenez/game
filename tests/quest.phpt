<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Quest;

class QuestModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Quest $model) {
    $this->model = $model;
  }
  
  function testListOfQuests() {
    $quests = $this->model->listOfQuests();
    Assert::type("array", $quests);
    Assert::type(Quest::class, $quests[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $quest = $this->model->view($id);
    Assert::type(Quest::class, $quest);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testStatus(int $id) {
    $result = $this->model->status($id);
    Assert::type("integer", $result);
    Assert::same(1, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testIsFinished(int $id) {
    $result = $this->model->isFinished($id);
    Assert::type("bool", $result);
    Assert::false($result);
  }
}
?>