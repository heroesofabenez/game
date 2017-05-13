<?php
namespace HeroesofAbenez\NPC;

interface INPCDialogueControlFactory {
  /** @return \HeroesofAbenez\NPC\NPCDialogueControl */
  function create();
}
?>