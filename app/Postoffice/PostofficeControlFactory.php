<?php
declare(strict_types=1);

namespace HeroesofAbenez\Postoffice;

interface PostofficeControlFactory
{
    public function create(): PostofficeControl;
}
