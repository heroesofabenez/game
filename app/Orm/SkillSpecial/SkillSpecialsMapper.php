<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialsMapper
 *
 * @author Jakub Konečný
 */
class SkillSpecialsMapper extends \Nextras\Orm\Mapper\Mapper {
  function getTableName() {
    return "skills_specials";
  }
}
?>