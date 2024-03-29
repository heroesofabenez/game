<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * @author Jakub Konečný
 */
final class CombatLogManagerTest extends \Tester\TestCase {
  private CombatLogManager $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(CombatLogManager::class);
  }
  
  public function testReadAndWrite() {
    Assert::null($this->model->read(5000));
    $id = $this->model->write("");
    Assert::type("int", $id);
    Assert::type(\HeroesofAbenez\Orm\Combat::class, $this->model->read($id));
  }
}

$test = new CombatLogManagerTest();
$test->run();
?>