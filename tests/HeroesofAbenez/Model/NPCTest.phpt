<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Npc as NpcEntity;

require __DIR__ . "/../../bootstrap.php";

class NPCTest extends \Tester\TestCase {
  /** @var NPC */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(NPC::class);
  }
  
  public function testView() {
    $npc = $this->model->view(1);
    Assert::type(NpcEntity::class, $npc);
    Assert::same("Mentor", $npc->name);
    Assert::same(2, $npc->race->id);
  }
}

$test = new NPCTest;
$test->run();
?>