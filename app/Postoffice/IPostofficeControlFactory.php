<?php
namespace HeroesofAbenez\Postoffice;

interface IPostofficeControlFactory {
  /** @return PostofficeControl */
  function create();
}
?>