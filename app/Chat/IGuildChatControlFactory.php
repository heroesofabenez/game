<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface IGuildChatControlFactory
{
    public function create(): GuildChatControl;
}
