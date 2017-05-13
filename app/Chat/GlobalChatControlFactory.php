<?php
namespace HeroesofAbenez\Chat;

interface GlobalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GlobalChatControl */
  function create();
}
?>