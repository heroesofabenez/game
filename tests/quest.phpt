<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

class QuestModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Quest */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Quest $model) {
    $this->model = $model;
  }
  
  function testListOfQuests() {
    $quests = $this->model->listOfQuests();
    Assert::type("array", $quests);
    Assert::type("HeroesofAbenez\Entities\Quest", $quests[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $quest = $this->model->view($id);
    Assert::type("HeroesofAbenez\Entities\Quest", $quest);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testStatus($id) {
    $result = $this->model->status($id);
    Assert::type("integer", $result);
    Assert::same(1, $result);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testIsFinished($id) {
    $result = $this->model->isFinished($id);
    Assert::type("bool", $result);
    Assert::false($result);
  }
}

/*$suit = new QuestModelTest($container->getService("hoa.model.quest"));
$suit->run();*/
?>