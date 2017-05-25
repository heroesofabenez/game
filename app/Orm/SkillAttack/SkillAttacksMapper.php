<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillAttacksMapper
 *
 * @author Jakub Konečný
 */
class SkillAttacksMapper extends \Nextras\Orm\Mapper\Mapper {
  /**
   * @return string
   */
  function getTableName(): string {
    return "skills_attacks";
  }
}
?>