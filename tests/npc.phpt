<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\NPC;

class NPCModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\NPC */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\NPC $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $npc = $this->model->view($id);
    Assert::type(NPC::class, $npc);
    Assert::same("Mentor", $npc->name);
    Assert::same(2, $npc->race);
  }
}
?>