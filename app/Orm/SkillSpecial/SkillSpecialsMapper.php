<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialsMapper
 *
 * @author Jakub Konečný
 */
class SkillSpecialsMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName() {
    return "skills_specials";
  }
}
?>