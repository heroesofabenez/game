<?php
namespace HeroesofAbenez\Chat;

interface IGuildChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GuildChatControl */
  public function create();
}
?>