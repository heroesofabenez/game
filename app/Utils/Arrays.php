<?php
declare(strict_types=1);

namespace HeroesofAbenez\Utils;

final class Arrays
{
    private function __construct()
    {
    }

    /**
     * @author jimpoz jimpoz@jimpoz.com
     * @license http://creativecommons.org/licenses/by/3.0/ CC-BY-3.0
     */
    public static function orderby(): array
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
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
