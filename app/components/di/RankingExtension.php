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
    $builder->addDefinition($this->prefix("characters"))
      ->setImplement("HeroesofAbenez\Ranking\CharactersRankingControlFactory");
    $builder->addDefinition($this->prefix("guilds"))
      ->setImplement("HeroesofAbenez\Ranking\GuildsRankingControlFactory");
  }
}
?>