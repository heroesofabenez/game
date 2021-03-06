<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialsMapper
 *
 * @author Jakub Konečný
 */
final class SkillSpecialsMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName(): string {
    return "skills_specials";
  }
}
?>