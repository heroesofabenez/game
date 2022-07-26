<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Quest as QuestEntity;
use HeroesofAbenez\Orm\CharacterQuest;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class QuestTest extends \Tester\TestCase {
  use TCharacterControl;

  private Quest $model;
  
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

  public function testGetCharacterQuest() {
    $result = $this->model->getCharacterQuest(3);
    Assert::type(CharacterQuest::class, $result);
    Assert::same(CharacterQuest::PROGRESS_STARTED, $result->progress);
    $result = $this->model->getCharacterQuest(4);
    Assert::type(CharacterQuest::class, $result);
    Assert::same(CharacterQuest::PROGRESS_OFFERED, $result->progress);
  }
  
  public function testStatus() {
    Assert::same(CharacterQuest::PROGRESS_STARTED, $this->model->status(3));
    Assert::same(CharacterQuest::PROGRESS_OFFERED, $this->model->status(5000));
  }
  
  public function testIsFinished() {
    Assert::false($this->model->isFinished(1));
  }

  public function testIsCompleted() {
    $characterQuest = new CharacterQuest();
    $characterQuest->quest = $this->model->view(3);
    $characterQuest->character = $this->getCharacter();
    $oldCostMoney = $characterQuest->quest->neededMoney;
    $oldNeededItem = $characterQuest->quest->neededItem;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $characterQuest->quest->neededMoney = $characterQuest->character->money + 1;
    Assert::false($this->model->isCompleted($characterQuest));
    $characterQuest->quest->neededMoney = $oldCostMoney;
    Assert::false($this->model->isCompleted($characterQuest));
    $characterQuest->quest->neededItem = null;
    Assert::true($this->model->isCompleted($characterQuest));
    $characterQuest->quest->neededItem = $oldNeededItem;
    $orm->quests->persistAndFlush($characterQuest->quest);
  }

  public function testFinish() {
    Assert::exception(function() {
      $this->model->finish(5000, 1);
    }, QuestNotFoundException::class);
    Assert::exception(function() {
      $this->model->finish(2, 1);
    }, QuestNotStartedException::class);
    Assert::exception(function() {
      $this->model->finish(3, 2);
    }, CannotFinishQuestHereException::class);
    Assert::exception(function() {
      $this->model->finish(3, 1);
    }, QuestNotFinishedException::class);
  }

  public function testIsAvailable() {
    /** @var QuestEntity $quest */
    $quest = $this->model->view(1);
    $oldRequiredLevel = $quest->requiredLevel;
    $oldRequiredClass = $quest->requiredClass;
    $oldRequiredRace = $quest->requiredRace;
    $oldRequiredQuest = $quest->requiredQuest;
    $user = $this->getCharacter();
    $quest->requiredLevel = $user->level + 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->requiredLevel = $oldRequiredLevel;
    $quest->requiredClass = 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->requiredClass = $oldRequiredClass;
    $quest->requiredRace = 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->requiredRace = $oldRequiredRace;
    $quest->requiredQuest = 1;
    Assert::false($this->model->isAvailable($quest));
    $quest->requiredQuest = $oldRequiredQuest;
    Assert::true($this->model->isAvailable($quest));
  }

  public function testAccept() {
    Assert::exception(function() {
      $this->model->accept(5000, 1);
    }, QuestNotFoundException::class);
    Assert::exception(function() {
      $this->model->accept(3, 1);
    }, QuestAlreadyStartedException::class);
    Assert::exception(function() {
      $this->model->accept(4, 2);
    }, CannotAcceptQuestHereException::class);
    Assert::exception(function() {
      $this->model->accept(4, 1);
    }, QuestNotAvailableException::class);
  }

  public function testGetRequirements() {
    /** @var QuestEntity $quest */
    $quest = $this->model->view(3);
    $oldNeededMoney = $quest->neededMoney;
    $oldNpcEnd = $quest->npcEnd;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $requirements = $this->model->getRequirements($quest);
    Assert::type("array", $requirements);
    Assert::count(2, $requirements);
    Assert::contains("get 1x ", $requirements[0]->text);
    Assert::contains("Spell casting for dummies", $requirements[0]->text);
    Assert::contains("report back to ", $requirements[1]->text);
    Assert::contains("Mentor", $requirements[1]->text);
    $quest->npcEnd = 2;
    $requirements = $this->model->getRequirements($quest);
    Assert::contains("talk to ", $requirements[1]->text);
    Assert::contains("Librarian", $requirements[1]->text);
    $quest->npcEnd = $oldNpcEnd;
    $quest->neededMoney = 1;
    $requirements = $this->model->getRequirements($quest);
    Assert::count(3, $requirements);
    Assert::same("pay 1 silver mark", $requirements[0]->text);
    $quest->neededMoney = $oldNeededMoney;
    $orm->quests->persistAndFlush($quest);
  }
}

$test = new QuestTest();
$test->run();
?>