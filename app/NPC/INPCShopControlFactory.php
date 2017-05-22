<?php
namespace HeroesofAbenez\NPC;

interface INPCShopControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCShopControl */
  function create();
}
?>