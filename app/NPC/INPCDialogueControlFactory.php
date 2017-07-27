<?php
namespace HeroesofAbenez\NPC;

interface INPCDialogueControlFactory {
  public function create(): NPCDialogueControl;
}
?>