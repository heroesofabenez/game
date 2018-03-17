<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface ILocalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\LocalChatControl */
  public function create();
}
?>