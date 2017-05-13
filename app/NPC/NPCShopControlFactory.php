<?php
namespace HeroesofAbenez\NPC;

interface NPCShopControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCQuestsControl */
  function create();
}
?>