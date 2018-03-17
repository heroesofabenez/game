<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface IGlobalChatControlFactory {
  public function create(): GlobalChatControl;
}
?>