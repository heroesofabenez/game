<?php
namespace HeroesofAbenez\Postoffice;

interface IPostofficeControlFactory {
  function create(): PostofficeControl;
}
?>