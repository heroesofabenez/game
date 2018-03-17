<?php
declare(strict_types=1);

namespace HeroesofAbenez\NPC;

interface INPCQuestsControlFactory {
  public function create(): NPCQuestsControl;
}
?>