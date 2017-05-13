<?php
namespace HeroesofAbenez\Chat;

interface LocalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\LocalChatControl */
  function create();
}
?>