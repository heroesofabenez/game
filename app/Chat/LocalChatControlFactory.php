<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface LocalChatControlFactory
{
    public function create(): LocalChatControl;
}
