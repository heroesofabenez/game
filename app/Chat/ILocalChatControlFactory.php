<?php
namespace HeroesofAbenez\Chat;

interface ILocalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\LocalChatControl */
  public function create();
}
?>