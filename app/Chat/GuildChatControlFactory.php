<?php
namespace HeroesofAbenez\Chat;

interface GuildChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GuildChatControl */
  function create();
}
?>