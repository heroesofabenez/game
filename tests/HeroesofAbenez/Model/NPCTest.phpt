<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Npc as NpcEntity;

require __DIR__ . "/../../bootstrap.php";

final class NPCTest extends \Tester\TestCase {
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
  
  public function testGetNpcName() {
    Assert::same("", $this->model->getNpcName(5000));
    Assert::same("Mentor", $this->model->getNpcName(1));
  }
}

$test = new NPCTest;
$test->run();
?>