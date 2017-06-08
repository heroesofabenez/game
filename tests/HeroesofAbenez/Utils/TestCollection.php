<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

class TestCollection extends Collection {
  
  function __construct() {
    $this->class = Item::class;
  }
}
?>