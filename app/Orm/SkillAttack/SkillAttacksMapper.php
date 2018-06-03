<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillAttacksMapper
 *
 * @author Jakub Konečný
 */
final class SkillAttacksMapper extends \Nextras\Orm\Mapper\Mapper {
  /**
   * @return string
   */
  public function getTableName(): string {
    return "skills_attacks";
  }
}
?>