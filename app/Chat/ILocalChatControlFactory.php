<?php
namespace HeroesofAbenez\Chat;

interface ILocalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\LocalChatControl */
  function create();
}
?>