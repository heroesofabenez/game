<?php
namespace HeroesofAbenez\Ranking\DI;

/**
 * Ranking Extension
 *
 * @author Jakub Konečný
 */
class RankingExtension extends \Nette\DI\CompilerExtension {
  function loadConfiguration() {
    $builder = $this->getContainerBuilder();
    $builder->addDefinition($this->prefix("guilds"))
      ->setImplement("HeroesofAbenez\Ranking\GuildsRankingControlFactory");
    $builder->addDefinition($this->prefix("ranking"))
      ->setImplement("HeroesofAbenez\Ranking\CharactersRankingControlFactory");
  }
}
?>