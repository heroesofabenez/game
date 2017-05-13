<?php
namespace HeroesofAbenez\NPC;

interface INPCShopControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCQuestsControl */
  function create();
}
?>