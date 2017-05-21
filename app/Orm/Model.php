<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Orm Model
 *
 * @author Jakub Konečný
 * @property-read GuildsRepository $guilds
 * @property-read GuildRanksRepository $guildRanks
 * @property-read GuildRanksCustomRepository $guildRanksCustom
 * @property-read GuildPrivilegesRepository $guildPrivileges
 * @property-read CharacterRacesRepository $races
 * @property-read CharacterClassesRepository $classes
 * @property-read CharacterSpecializationsRepository $specializations
 */
class Model extends \Nextras\Orm\Model\Model {
  
}
?>