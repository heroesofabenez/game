<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Npc as NpcEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class NPCTest extends \Tester\TestCase {
  private NPC $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(NPC::class);
  }
  
  public function testView() {
    Assert::null($this->model->view(5000));
    $npc = $this->model->view(1);
    Assert::type(NpcEntity::class, $npc);
    Assert::same("Mentor", $npc->name);
    Assert::same(2, $npc->race->id);
  }
}

$test = new NPCTest();
$test->run();
?>