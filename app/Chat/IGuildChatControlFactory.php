<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface IGuildChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GuildChatControl */
  public function create();
}
?>