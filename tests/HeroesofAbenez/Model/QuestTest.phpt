<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\QuestDummy as QuestEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class QuestTest extends \Tester\TestCase {
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
  }
  
  /**
   * @return void
   */
  public function testView() {
    $quest = $this->model->view(1);
    Assert::type(QuestEntity::class, $quest);
  }
  
  /**
   * @return void
   */
  public function testStatus() {
    $result = $this->model->status(1);
    Assert::type("integer", $result);
    Assert::same(1, $result);
  }
  
  /**
   * @return void
   */
  public function testIsFinished() {
    $result = $this->model->isFinished(1);
    Assert::type("bool", $result);
    Assert::false($result);
  }
}

$test = new QuestTest;
$test->run();
?>