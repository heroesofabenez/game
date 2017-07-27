<?php
namespace HeroesofAbenez\Postoffice;

interface IPostofficeControlFactory {
  public function create(): PostofficeControl;
}
?>