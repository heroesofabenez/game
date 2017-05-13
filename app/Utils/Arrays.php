<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

class Arrays {
  use \Nette\StaticClass;
  
  /**
   * @author jimpoz jimpoz@jimpoz.com
   * @license http://creativecommons.org/licenses/by/3.0/ CC-BY-3.0
   * @return array
   */
  static function orderby(): array {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
      if(is_string($field)) {
        $tmp = [];
        foreach ($data as $key => $row) {
          $tmp[$key] = $row[$field];
        }
        $args[$n] = $tmp;
      }
    }
    $args[] = &$data;
    call_user_func_array("array_multisort", $args);
    return array_pop($args);
  }
}
?>