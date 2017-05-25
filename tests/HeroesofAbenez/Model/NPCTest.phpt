<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Npc as NpcEntity;

class NPCTest extends MT\TestCase {
  /** @var NPC */
  protected $model;
  
  function __construct(NPC $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $npc = $this->model->view($id);
    Assert::type(NpcEntity::class, $npc);
    Assert::same("Mentor", $npc->name);
    Assert::same(2, $npc->race->id);
  }
}
?>