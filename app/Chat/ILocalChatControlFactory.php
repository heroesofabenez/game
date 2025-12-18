<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

interface ILocalChatControlFactory
{
    public function create(): LocalChatControl;
}
