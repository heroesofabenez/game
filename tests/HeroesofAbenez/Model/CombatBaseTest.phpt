<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Entities\Team;

class CombatBaseTest extends MT\TestCase {
  /** @var  CombatLogger */
  protected $logger;
  /** @var  CombatHelper */
  protected $helper;
  
  function __construct(CombatLogger $logger, CombatHelper $helper) {
    $this->logger = $logger;
    $this->helper = $helper;
  }
  
  /**
   * @return void
   */
  function testPostCombat() {
    $combat = new CombatBase(clone $this->logger);
    $team1 = new Team("Team 1");
    $team1[] = $this->helper->getPlayer(1);
    $team2 = new Team("Team 2");
    $team2[] = $this->helper->getPlayer(2);
    $combat->setTeams($team1, $team2);
    $combat->execute();
    Assert::true($combat->round);
    Assert::true(($combat->round <= 31));
    Assert::true($combat->log->round);
    Assert::same(5000, $combat->log->round);
    $players = array_merge($team1->items, $team2->items);
    /** @var Character $player */
    foreach($players as $player) {
      Assert::same(0, $player->initiative);
    }
  }
}
?>