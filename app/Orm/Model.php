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
 * @property-read PetTypesRepository $petTypes
 * @property-read QuestAreasRepository $areas
 * @property-read RoutesAreasRepository $areaRoutes
 * @property-read QuestStagesRepository $stages
 * @property-read RoutesStagesRepository $stageRoutes
 * @property-read CombatsRepository $combats
 * @property-read NpcsRepository $npcs
 * @property-read ItemsRepository $items
 * @property-read ShopItemsRepository $shopItems
 */
class Model extends \Nextras\Orm\Model\Model {
  
}
?>