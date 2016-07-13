<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

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
  function testView($id) {
    $npc = $this->model->view($id);
    Assert::type("\HeroesofAbenez\Entities\NPC", $npc);
    Assert::same("Mentor", $npc->name);
    Assert::same(2, $npc->race);
  }
}

/*$suit = new NPCModelTest($container->getService("hoa.model.npc"));
$suit->run();*/
?>