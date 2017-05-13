<?php
namespace HeroesofAbenez\Postoffice;

interface PostofficeControlFactory {
  /** @return PostofficeControl */
  function create();
}
?>