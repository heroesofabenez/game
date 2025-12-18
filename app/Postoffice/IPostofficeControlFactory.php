<?php
declare(strict_types=1);

namespace HeroesofAbenez\Postoffice;

interface IPostofficeControlFactory
{
    public function create(): PostofficeControl;
}
