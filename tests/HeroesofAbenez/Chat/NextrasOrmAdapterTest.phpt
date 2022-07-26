<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class NextrasOrmAdapterTest extends \Tester\TestCase {
  use \Testbench\TCompiledContainer;
  
  /** @var NextrasOrmAdapter */
  protected $model;
  /** @var \HeroesofAbenez\Orm\Model */
  protected $orm;
  
  protected function setUp() {
    $this->model = $this->getService(NextrasOrmAdapter::class);
    $this->orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
  }
  
  public function testGetTexts() {
    $texts = $this->model->getTexts("guild", 1, 25);
    Assert::type(ChatMessagesCollection::class, $texts);
    Assert::count(4, $texts);
  }
  
  public function testGetCharacters() {
    $characters = $this->model->getCharacters("guild", 1);
    Assert::type(ChatCharactersCollection::class, $characters);
  }
  
  public function testAddMessage() {
    $texts = $this->model->getTexts("guild", 1, 25);
    Assert::count(4, $texts);
    $this->model->addMessage("test", "guild", 1);
    $texts = $this->model->getTexts("guild", 1, 25);
    Assert::count(5, $texts);
    $this->orm->chatMessages->removeAndFlush($this->orm->chatMessages->getById($texts[0]->id));
  }
}

$test = new NextrasOrmAdapterTest();
$test->run();
?>