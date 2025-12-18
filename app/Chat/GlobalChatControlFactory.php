<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface GlobalChatControlFactory
{
    public function create(): GlobalChatControl;
}
