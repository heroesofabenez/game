<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Orm Model
 *
 * @author Jakub Konečný
 * @property-read CharactersRepository $characters
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
 * @property-read RequestsRepository $requests
 * @property-read MessagesRepository $messages
 * @property-read IntroductionsRepository $introduction
 * @property-read PetsRepository $pets
 * @property-read PveArenaOpponentsRepository $arenaNpcs
 * @property-read ArenaFightsCountRepository $arenaFightsCount
 * @property-read CharacterItemsRepository $characterItems
 * @property-read QuestsRepository $quests
 * @property-read CharacterQuestsRepository $characterQuests
 * @property-read ChatBansRepository $chatBans
 * @property-read ChatMessagesRepository $chatMessages
 * @property-read SkillAttacksRepository $attackSkills
 * @property-read SkillSpecialsRepository $specialSkills
 * @property-read CharacterAttackSkillsRepository $characterAttackSkills
 * @property-read CharacterSpecialSkillsRepository $characterSpecialSkills
 * @property-read PveArenaOpponentEquipmentRepository $arenaNpcsEquipment
 */
final class Model extends \Nextras\Orm\Model\Model {
  
}
?>