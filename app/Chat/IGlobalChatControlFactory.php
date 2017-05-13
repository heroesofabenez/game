<?php
namespace HeroesofAbenez\Chat;

interface IGlobalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GlobalChatControl */
  function create();
}
?>