SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `character_classes` (`id`, `name`, `strength`, `strength_grow`, `dexterity`, `dexterity_grow`, `constitution`, `constitution_grow`, `intelligence`, `intelligence_grow`, `charisma`, `charisma_grow`, `stat_points_level`, `initiative`) VALUES
(1,	'fighter',	11,	0.25,	10,	0.1,	12,	0.5,	9,	0.1,	9,	0.1,	1,	'1d5+DEX/4'),
(2,	'rogue',	11,	0.25,	12,	0.5,	8,	0.1,	10,	0.1,	10,	0.1,	1,	'2d3+DEX/4'),
(3,	'wizard',	9,	0,	10,	0.1,	9,	0.1,	12,	0.5,	11,	0.25,	1.25,	'5d2+INT/3'),
(4,	'archer',	9,	0.1,	12,	0.5,	9,	0.1,	11,	0.25,	10,	0.1,	1,	'4d2+DEX/4');

INSERT INTO `character_races` (`id`, `name`, `strength`, `dexterity`, `constitution`, `intelligence`, `charisma`) VALUES
(1,	'barbarian',1,	0,	1,	-1,	-1),
(2,	'human',	0,	0,	0,	0,	0),
(3,	'elf',	-1,	1,	-2,	1,	2),
(4,	'dwarf',	1,	-1,	2,	-1,	-1);

INSERT INTO `character_specializations` (`id`, `name`, `class`, `strength_grow`, `dexterity_grow`, `constitution_grow`, `intelligence_grow`, `charisma_grow`, `stat_points_level`) VALUES
(1,	'warrior',	1,	0.6,	0.2,	0.3,	0.1,	0.1,	1.2),
(2,	'paladin',	1,	0.2,	0.1,	0.75,	0.25,	0.2,	1),
(3,	'executioner',	2,	0.25,	0.75,	0.2,	0.2,	0.1,	1),
(4,	'spy',	2,	0.25,	0.6,	0.2,	0.25,	0.2,	1),
(5,	'hunter',	4,	0.1,	0.75,	0.2,	0.35,	0.1,	1),
(6,	'bowman',	4,	0.3,	0.5,	0.3,	0.2,	0.2,	1),
(7,	'ranger',	4,	0.2,	0.5,	0.4,	0.3,	0.1,	1),
(8,	'sorcerer',	3,	0,	0.2,	0.2,	0.75,	0.25,	1.1),
(9,	'mystic',	3,	0.1,	0.3,	0.3,	0.6,	0.2,	1);

INSERT INTO `equipment` (`id`, `name`, `slot`, `type`, `required_level`, `required_class`, `price`, `strength`, `durability`) VALUES
(1,	'Novice\'s Sword',	'weapon',	'sword',	1,	1,	0,	1,	10),
(2,	'Novice\'s Axe',	'weapon',	'axe',	1,	1,	0,	2,	10),
(3,	'Rookie\'s Dagger',	'weapon',	'dagger',	1,	2,	0,	1,	10),
(4,	'Rookie\'s Knife',	'weapon',	'throwing knife',	1,	2,	0,	1,	10),
(5,	'Apprentice\'s Wand',	'weapon',	'staff',	1,	3,	0,	1,	10),
(6,	'Novice\'s Bow',	'weapon',	'bow',	1,	4,	0,	1,	10),
(7,	'Leather Armor',	'armor',	'',	1,	1,	0,	2,	15),
(8,	'Rookie\'s Cloak',	'armor',	'',	1,	2,	0,	1,	10),
(9,	'Apprentice\'s Robe',	'armor',	'',	1,	3,	0,	1,	10),
(10,	'Green Cloak',	'armor',	'',	1,	4,	0,	1,	10);

INSERT INTO `guild_privileges` (`id`, `action`, `rank`) VALUES
(1,	'manage',	5),
(2,	'invite',	4),
(3,	'promote',	5),
(4,	'rename',	6),
(5,	'kick',	6),
(6,	'dissolve',	7),
(7,	'changeRankNames',	7);

INSERT INTO `guild_ranks` (`id`, `name`) VALUES
(1,	'recruit'),
(2,	'member'),
(3,	'regular'),
(4,	'advisor'),
(5,	'master'),
(6,	'deputy'),
(7,	'grandmaster');

INSERT INTO `introduction` (`id`, `race`, `class`, `part`, `text`) VALUES
(1,	2,	3,	1,	'Part 1'),
(2,	2,	3,	2,	'ENDOFINTRO');

INSERT INTO `items` (`id`, `name`, `image`, `price`) VALUES
(1,	'Book ABC',	'book-abc.jpeg',	0);

INSERT INTO `pet_types` (`id`, `name`, `bonus_stat`, `bonus_value`, `image`, `required_level`, `required_class`, `required_race`, `cost`) VALUES
(1,	'Rescued Lion',	'con',	5,	'',	8,	1,	NULL,	0),
(2,	'Rescued Snake',	'dex',	5,	'',	8,	2,	NULL,	0),
(3,	'Rescued Owl',	'int',	5,	'',	8,	3,	NULL,	0),
(4,	'Rescued Hawk',	'dex',	5,	'',	8,	4,	NULL,	0),
(5,	'Squire\'s Lion',	'con',	10,	'',	15,	1,	NULL,	0),
(6,	'Squire\'s Tiger',	'str',	10,	'',	15,	1,	NULL,	0),
(7,	'Squire\'s Snake',	'dex',	10,	'',	15,	2,	NULL,	0),
(8,	'Squire\'s Owl',	'int',	10,	'',	15,	3,	NULL,	0),
(9,	'Squire\'s Hawk',	'dex',	10,	'',	15,	4,	NULL,	0),
(10,	'Knight\'s Lion',	'con',	15,	'',	30,	1,	NULL,	0),
(11,	'Knight\'s Tiger',	'str',	15,	'',	30,	1,	NULL,	0),
(12,	'Knight\'s Snake',	'dex',	15,	'',	30,	2,	NULL,	0),
(13,	'Knight\'s Owl',	'int',	15,	'',	30,	3,	NULL,	0),
(14,	'Knight\'s Hawk',	'dex',	15,	'',	30,	4,	NULL,	0),
(15,	'Royal Lion',	'con',	20,	'',	45,	1,	NULL,	0),
(16,	'Royal Tiger',	'str',	20,	'',	45,	1,	NULL,	0),
(17,	'Royal Snake',	'dex',	20,	'',	45,	2,	NULL,	0),
(18,	'Royal Owl',	'int',	20,	'',	45,	3,	NULL,	0),
(19,	'Royal Hawk',	'dex',	20,	'',	45,	4,	NULL,	0);

INSERT INTO `pve_arena_opponents` (`id`, `name`, `race`, `gender`, `occupation`, `level`, `strength`, `dexterity`, `constitution`, `intelligence`, `charisma`, `weapon`, `armor`) VALUES
  (1,	'Div Fast-hands',	2,	'male',	2,	2,	11,	13,	8,	10,	10,	3,	8),
  (2,	'El-Tovil',	4,	'male',	1,	2,	13,	9,	14,	8,	8,	2,	7),
  (3,	'Valiana',	3,	'female',	3,	2,	8,	11,	7,	15,	13,	5,	9),
  (4,	'Alinia',	3,	'female',	4,	3,	8,	14,	8,	12,	12,	6,	10),
  (5,	'Eldan',	1,	'male',	1,	5,	13,	10,	15,	8,	8,	1,	7);

INSERT INTO `quests` (`id`, `name`, `cost_money`, `needed_item`, `needed_quest`, `needed_level`, `item_amount`, `item_lose`, `reward_money`, `reward_xp`, `reward_item`, `reward_white_karma`, `reward_dark_karma`, `reward_pet`, `npc_start`, `npc_end`, `order`) VALUES
  (1,	'Find a book',	0,	1,	NULL,	NULL,	1,	0,	0,	10,	NULL, 0, 0, NULL,	1,	1,	1);

INSERT INTO `quest_areas` (`id`, `name`, `description`, `required_level`, `required_race`, `required_occupation`, `pos_x`, `pos_y`) VALUES
(1,	'Academy of Magic',	'm',	0,	NULL,	3,	220,	35),
(2,	'Sands of Ramir - borderlands',	'sands',	0,	3,	NULL,	220,	153),
(3,	'Border woods',	'b',	25,	NULL,	NULL,	80,	165),
(4,	'North Great Horde',	'n',	30,	NULL,	NULL,	175,	109);

INSERT INTO `quest_stages` (`id`, `name`, `description`, `required_level`, `required_race`, `required_occupation`, `area`, `pos_x`, `pos_y`) VALUES
(1,	'Your cell',	'y',	0,	NULL,	3,	1,	215,	65),
(2,	'Hall',	'x',	0,	NULL,	3,	1,	115,	215),
(3,	'Library',	'x',	0,	NULL,	3,	1,	119,	37);

INSERT INTO `routes_stages` (`id`, `from`, `to`) VALUES
(1,	1,	2),
(2,	1,	3),
(3,	2,	3);

INSERT INTO `shop_items` (`id`, `npc`, `item`, `order`) VALUES
  (1,	2,	1,	1);

INSERT INTO `skills_attacks` (`id`, `name`, `needed_class`, `needed_specialization`, `needed_level`, `base_damage`, `damage_growth`, `levels`, `target`, `strikes`, `hit_rate`) VALUES
(1,	'Assault',	1,	NULL,	1,	'110%',	'5%',	5,	'single',	1,	NULL),
(2,	'Shadow strike',	2,	NULL,	1,	'61%',	'2%',	5,	'single',	2,	NULL),
(3,	'Blast',	3,	NULL,	1,	'115%',	'5%',	5,	'single',	1,	NULL),
(4,	'Rain of arrows',	4,	NULL,	1,	'35%',	'2%',	5,	'single',	3,	NULL);

INSERT INTO `skills_specials` (`id`, `name`, `needed_class`, `needed_specialization`, `needed_level`, `type`, `target`, `stat`, `value`, `value_growth`, `levels`, `duration`) VALUES
(1,	'Shield',	1,	NULL,	1,	'buff',	'self',	'defense',	12,	2,	5,	3),
(2,	'Shadow protection',	2,	NULL,	1,	'buff',	'self',	'dodge',	15,	2,	5,	3),
(3,	'Quicken spell',	3,	NULL,	1,	'buff',	'self',	'initiative',	18,	2,	5,	3),
(4,	'Precision',	4,	NULL,	1,	'buff',	'self',	'hit',	15,	2,	5,	3),
(5,	'Cover',	1,	NULL,	10,	'buff',	'party',	'defense',	14,	1,	6,	4),
(6,	'Smoke',	2,	NULL,	10,	'debuff',	'enemy_party',	'hit',	14,	1,	6,	4),
(7,	'Entangle',	3,	NULL,	10,	'debuff',	'enemy',	'initiative',	13,	2,	6,	4),
(8,	'Evasion',	4,	NULL,	10,	'buff',	'party',	'dodge',	10,	1,	6,	4);

INSERT INTO `npcs` (`id`, `name`, `race`, `type`, `sprite`, `portrait`, `stage`, `karma`, `personality`, `level`, `pos_x`, `pos_y`) VALUES
  (1,	'Mentor',	2,	'quest',	'mentor.jpeg',	'mentor.jpeg',	1,	'neutral', 'teaching', 10,	1,	1),
  (2,	'Librarian',	2,	'shop',	'librarian.jpeg',	'librarian.jpeg',	3,	'neutral', 'friendly', 10,	1,	1);
