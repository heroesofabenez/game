<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface IGlobalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GlobalChatControl */
  public function create();
}
?>