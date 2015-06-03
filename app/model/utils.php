<?php
namespace HeroesofAbenez\Utils;

class Arrays {
  /**
   * @author jimpoz jimpoz@jimpoz.com
   * @license http://creativecommons.org/licenses/by/3.0/ CC-BY-3.0
   * @return array
   */
  static function orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
      if(is_string($field)) {
        $tmp = array();
        foreach ($data as $key => $row) {
          $tmp[$key] = $row[$field];
        }
        $args[$n] = $tmp;
        }
      }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
  }
}
?>