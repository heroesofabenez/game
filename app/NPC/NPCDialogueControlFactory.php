<?php
namespace HeroesofAbenez\NPC;

interface NPCDialogueControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCDialogueControl */
  function create();
}
?>