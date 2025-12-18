<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface GuildChatControlFactory
{
    public function create(): GuildChatControl;
}
