<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * IntroductionsMapper
 *
 * @author Jakub Konečný
 */
final class IntroductionsMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName(): string {
    return "introduction";
  }
}
?>