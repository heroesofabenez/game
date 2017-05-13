<?php
namespace HeroesofAbenez\NPC;

interface INPCQuestsControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCQuestsControl */
  function create();
}
?>