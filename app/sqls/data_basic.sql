SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `character_classes` (`id`, `name`, `strength`, `strength_grow`, `dexterity`, `dexterity_grow`, `constitution`, `constitution_grow`, `intelligence`, `intelligence_grow`, `charisma`, `charisma_grow`, `stat_points_level`, `initiative`, `playable`) VALUES
  (1,	'fighter',	1,	0.25,	0,	0.2,	2,	0.5,	-1,	0,	-1,	0.1,	1,	'1d5+DEX/4',	1),
  (2,	'rogue',	1,	0.25,	2,	0.5,	-2,	0,	0,	0.1,	0,	0.2,	1,	'2d3+DEX/4',	1),
  (3,	'wizard',	-1,	0,	0,	0.1,	-1,	0.1,	2,	0.5,	1,	0.25,	1.1,	'5d2+INT/3',	1),
  (4,	'archer',	-1,	0,	2,	0.5,	0,	0.1,	0,	0.25,	0,	0.1,	1.1,	'4d2+DEX/4',	1),
  (5,	'bard',	-1,	0,	1,	0.25,	-1,	0.1,	0,	0.1,	2,	0.5,	1.1,	'5d2+CHAR/3',	0),
  (6,	'preacher',	-1,	0,	-1,	0,	0,	0.2,	1,	0.25,	2,	0.5,	1.1,	'5d2+INT/3',	0);

INSERT INTO `character_races` (`id`, `name`, `strength`, `dexterity`, `constitution`, `intelligence`, `charisma`, `playable`) VALUES
  (1,	'barbarian',	11,	10,	11,	9,	9,	1),
  (2,	'human',	10,	10,	10,	10,	10,	1),
  (3,	'elf',	9,	11,	8,	11,	12,	1),
  (4,	'dwarf',	11,	9,	12,	9,	9,	1),
  (5,	'orc',	11,	11,	10,	9,	8,	0);

INSERT INTO `character_specializations` (`id`, `name`, `class`, `strength_grow`, `dexterity_grow`, `constitution_grow`, `intelligence_grow`, `charisma_grow`, `stat_points_level`) VALUES
(1,	'berserker',	1,	0.7,	0.2,	0.4,	0.0,	0.1,	1.1),
(2,	'paladin',	1,	0.2,	0,	0.75,	0.25,	0.3,	1),
(3,	'executioner',	2,	0.25,	0.75,	0.2,	0.2,	0.1,	1),
(4,	'spy',	2,	0.25,	0.6,	0.2,	0.25,	0.2,	1),
(5,	'ranger',	4,	0.1,	0.75,	0.2,	0.35,	0.1,	1),
(6,	'bowman',	4,	0.3,	0.5,	0.3,	0.2,	0.2,	1),
(7,	'sorcerer',	3,	0,	0.2,	0.2,	0.75,	0.35,	1),
(8,	'mystic',	3,	0.1,	0.3,	0.3,	0.6,	0.2,	1),
(9,	'priest',	6,	0,	0,	0.25,	0.5,	0.75,	1),
(10,	'monk',	6,	0,	0,	0.5,	0.75,	0.25,	1),
(11,	'troubadour',	5,	0,	0.4,	0.1,	0.25,	0.75,	1),
(12,	'trickster',	5,	0,	0.4,	0,	0.5,	0.6,	1);

INSERT INTO `guild_privileges` (`id`, `action`, `rank`) VALUES
(1,	'manage',	5),
(2,	'invite',	4),
(3,	'promote',	5),
(4,	'rename',	6),
(5,	'kick',	6),
(6,	'dissolve',	7),
(7,	'changeRankNames',	6);

INSERT INTO `guild_ranks` (`id`, `name`) VALUES
(1,	'recruit'),
(2,	'member'),
(3,	'regular'),
(4,	'advisor'),
(5,	'master'),
(6,	'deputy'),
(7,	'grandmaster');

INSERT INTO `introduction` (`id`, `race`, `class`, `part`, `text`) VALUES
(1,	2,	3,	1,	'Part 1');

INSERT INTO `items` (`id`, `name`, `slot`, `type`, `required_level`, `required_class`, `required_specialization`, `price`, `strength`, `durability`) VALUES
(1,	'Spell casting for dummies',	'item',	NULL,	1,	3,	NULL,	0,	0,	1),
(2,	'Novice\'s Sword',	'weapon',	'sword',	1,	1,	NULL,	0,	1,	10),
(3,	'Novice\'s Axe',	'weapon',	'axe',	1,	1,	NULL,	0,	2,	10),
(4,	'Rookie\'s Dagger',	'weapon',	'dagger',	1,	2,	NULL,	0,	1,	10),
(5,	'Rookie\'s Knife',	'weapon',	'throwing knife',	1,	2,	NULL,	0,	1,	10),
(6,	'Apprentice\'s Wand',	'weapon',	'staff',	1,	3,	NULL,	0,	1,	10),
(7,	'Novice\'s Bow',	'weapon',	'bow',	1,	4,	NULL,	0,	1,	10),
(8,	'Leather Armor',	'armor',	NULL,	1,	1,	NULL,	0,	2,	15),
(9,	'Rookie\'s Cloak',	'armor',	NULL,	1,	2,	NULL,	0,	1,	10),
(10,	'Apprentice\'s Robe',	'armor',	NULL,	1,	3,	NULL,	0,	1,	10),
(11,	'Green Cloak',	'armor',	NULL,	1,	4,	NULL,	0,	1,	10),
(12,	'Novice\'s Shield',	'shield',	NULL,	1,	1,	NULL,	5,	2,	15),
(13,	'Novice\'s Helmet',	'helmet',	NULL,	1,	1,	NULL,	5,	3,	15),
(14,	'Amulet of Swiftness',	'amulet',	NULL,	8,	NULL,	NULL,	30,	2,	10),
(15,	'Soldier\'s Sword',	'weapon',	'sword',	8,	1,	NULL,	13,	3,	20),
(16,	'Soldier\'s Axe',	'weapon',	'axe',	8,	1,	NULL,	13,	3,	20),
(17,	'Rogue\'s Dagger',	'weapon',	'dagger',	8,	2,	NULL,	13,	2,	20),
(18,	'Rogue\'s Knife',	'weapon',	'throwing knife',	8,	2,	NULL,	13,	2,	20),
(19,	'Soldier\'s Spear',	'weapon',	'spear',	8,	1,	NULL,	13,	3,	20),
(20,	'Rogue\'s Crossbow',	'weapon',	'crossbow',	8,	2,	NULL,	17,	3,	20),
(21,	'Wizard\'s Staff',	'weapon',	'staff',	8,	3,	NULL,	17,	3,	20),
(22,	'Archer\'s Bow',	'weapon',	'bow',	8,	4,	NULL,	13,	2,	20),
(23,	'Soldier\'s Armor',	'armor',	NULL,	8,	1,	NULL,	21,	3,	25),
(24,	'Rogue\'s Cloak',	'armor',	NULL,	8,	2,	NULL,	18,	2,	20),
(25,	'Wizard\'s Robe',	'armor',	NULL,	8,	3,	NULL,	18,	2,	20),
(26,	'Brown Cloak',	'armor',	NULL,	8,	4,	NULL,	18,	2,	20),
(27,	'Soldier\'s Helmet',	'helmet',	NULL,	8,	1,	NULL,	18,	6,	25),
(28,	'Wizard\'s Hat',	'helmet',	NULL,	8,	3,	NULL,	18,	4,	20),
(29,	'Soldier\'s Shield',	'shield',	NULL,	8,	1,	NULL,	15,	4,	25),
(30,	'Mallet',	'weapon',	'club',	8,	1,	NULL,	20,	4,	15),
(31,	'Ring of Accuracy',	'ring',	NULL,	8,	NULL,	NULL,	30,	2,	10),
(32,	'Cunning combat moves',	'item',	NULL,	1,	2,	NULL,	0,	0,	1),
(33,	'Advanced combat techniques',	'item',	NULL,	1,	1,	NULL,	0,	0,	1),
(34,	'Red shirt',	'armor',	NULL,	1,	5,	NULL,	0,	1,	10),
(35,	'Pipe',	'weapon',	'instrument',	1,	5,	NULL,	0,	1,	10),
(36,	'Flute',	'weapon',	'instrument',	8,	5,	NULL,	13,	2,	20),
(37,	'Drum',	'weapon',	'instrument',	8,	5,	NULL,	17,	3,	20),
(38,	'Violet shirt',	'armor',	NULL,	8,	5,	NULL,	0,	2,	20),
(39,	'Blessed Sword',	'weapon',	'sword',	15,	1,	2,	26,	4,	30),
(40,	'Blessed Maul',	'weapon',	'club',	15,	1,	2,	28,	4,	35),
(41,	'Blessed Armor',	'armor',	NULL,	15,	1,	2,	30,	5,	35),
(42,	'Blessed Shield',	'shield',	NULL,	15,	1,	2,	25,	7,	35),
(43,	'Blessed Helmet',	'helmet',	NULL,	15,	1,	2,	30,	10,	35),
(44,	'Bloody Axe',	'weapon',	'axe',	15,	1,	1,	26,	5,	30),
(45,	'Bloody Spear',	'weapon',	'spear',	15,	1,	1,	26,	5,	30),
(46,	'Bear Coat',	'armor',	NULL,	15,	1,	1,	26,	4,	30),
(47,	'Sorcerer\'s Staff',	'weapon',	'staff',	15,	3,	7,	28,	4,	30),
(48,	'Sorcerer\'s Coat',	'armor',	NULL,	15,	3,	7,	28,	3,	30),
(49,	'Sorcerer\'s Hat',	'helmet',	NULL,	15,	3,	7,	30,	6,	30),
(50,	'Amulet of High Speed',	'amulet',	NULL,	16,	NULL,	NULL,	50,	4,	20),
(51,	'Ring of Great Precision',	'ring',	NULL,	16,	NULL,	NULL,	50,	4,	20),
(52,	'Wanderer\'s Coat',	'armor',	NULL,	15,	3,	8,	28,	3,	30),
(53,	'Wanderer\'s Staff',	'weapon',	'staff',	15,	3,	8,	28,	4,	30),
(54,	'Forest Keeper\'s Cloak',	'armor',	NULL,	15,	4,	5,	28,	3,	30),
(55,	'Forest Keeper\'s Bow',	'weapon',	'bow',	15,	4,	5,	23,	3,	30),
(56,	'Slayer\'s Cloak',	'armor',	NULL,	15,	2,	3,	28,	3,	30),
(57,	'Slayer\'s Dagger',	'weapon',	'dagger',	15,	2,	3,	23,	3,	30),
(58,	'Slayer\'s Knife',	'weapon',	'throwing knife',	15,	2,	3,	23,	3,	30),
(59,	'Spy\'s Cloak',	'armor',	NULL,	15,	2,	4,	28,	3,	30),
(60,	'Spy\'s Dagger',	'weapon',	'dagger',	15,	2,	4,	23,	3,	30),
(61,	'Spy\'s Crossbow',	'weapon',	'crossbow',	15,	2,	4,	28,	4,	30),
(62,	'Amulet of Light Speed',	'amulet',	NULL,	30,	NULL,	NULL,	74,	7,	30),
(63,	'Preacher\'s Crook',	'weapon',	'staff',	1,	6,	NULL,	0,	1,	10),
(64,	'Preacher\'s Gown',	'armor',	NULL,	1,	6,	NULL,	0,	1,	10),
(65,	'Missionary\'s Crook',	'weapon',	'staff',	8,	6,	NULL,	13,	2,	20),
(66,	'Missionary\'s Gown',	'armor',	NULL,	8,	6,	NULL,	13,	2,	20),
(67,	'Priest\'s Crook',	'weapon',	'staff',	15,	6,	9,	23,	3,	30),
(68,	'Priest\'s Gown',	'armor',	NULL,	15,	6,	9,	28,	3,	20),
(69,	'Monk\'s Flail',	'weapon',	'club',	15,	6,	10,	28,	3,	30),
(70,	'Monk\'s Cowl',	'armor',	NULL,	15,	6,	10,	28,	3,	20),
(71,    'Treating injuries', 'item', NULL, 1, NULL, NULL, 10, 0, 1),
(72,    'Bandage', 'item', NULL, 1, NULL, NULL, 3, 0, 1),
(73,    'Graduate\'s gown', 'armor', NULL, 5, 3, NULL, 0, 2, 15),
(74,    'Graduation certificate', 'item', NULL, 5, 3, NULL, 0, 0, 1);

INSERT INTO `pet_types` (`id`, `name`, `bonus_stat`, `bonus_value`, `image`, `required_level`, `required_class`, `required_race`, `cost`) VALUES
(1,	'Rescued Lion',	'constitution',	5,	'',	8,	1,	NULL,	0),
(2,	'Rescued Snake',	'dexterity',	5,	'',	8,	2,	NULL,	0),
(3,	'Rescued Owl',	'intelligence',	5,	'',	8,	3,	NULL,	0),
(4,	'Rescued Hawk',	'dexterity',	5,	'',	8,	4,	NULL,	0),
(5,	'Squire\'s Lion',	'constitution',	10,	'',	15,	1,	NULL,	0),
(6,	'Squire\'s Tiger',	'strength',	10,	'',	15,	1,	NULL,	0),
(7,	'Squire\'s Snake',	'dexterity',	10,	'',	15,	2,	NULL,	0),
(8,	'Squire\'s Owl',	'intelligence',	10,	'',	15,	3,	NULL,	0),
(9,	'Squire\'s Hawk',	'dexterity',	10,	'',	15,	4,	NULL,	0),
(10,	'Knight\'s Lion',	'constitution',	15,	'',	30,	1,	NULL,	0),
(11,	'Knight\'s Tiger',	'strength',	15,	'',	30,	1,	NULL,	0),
(12,	'Knight\'s Snake',	'dexterity',	15,	'',	30,	2,	NULL,	0),
(13,	'Knight\'s Owl',	'intelligence',	15,	'',	30,	3,	NULL,	0),
(14,	'Knight\'s Hawk',	'dexterity',	15,	'',	30,	4,	NULL,	0),
(15,	'Royal Lion',	'constitution',	20,	'',	45,	1,	NULL,	0),
(16,	'Royal Tiger',	'strength',	20,	'',	45,	1,	NULL,	0),
(17,	'Royal Snake',	'dexterity',	20,	'',	45,	2,	NULL,	0),
(18,	'Royal Owl',	'intelligence',	20,	'',	45,	3,	NULL,	0),
(19,	'Royal Hawk',	'dexterity',	25,	'',	45,	4,	NULL,	0),
(20,	'Imperial Lion',	'constitution',	25,	'',	60,	1,	NULL,	0),
(21,	'Imperial Tiger',	'strength',	25,	'',	60,	1,	NULL,	0),
(22,	'Imperial Snake',	'dexterity',	25,	'',	60,	2,	NULL,	0),
(23,	'Imperial Owl',	'intelligence',	25,	'',	60,	3,	NULL,	0),
(24,	'Imperial Hawk',	'dexterity',	25,	'',	60,	4,	NULL,	0);

INSERT INTO `pve_arena_opponents` (`id`, `name`, `race`, `gender`, `class`, `specialization`, `level`) VALUES
  (1,	'Div Fast-hands',	2,	'male',	2,	NULL,	2),
  (2,	'El-Tovil',	4,	'male',	1,	NULL,	2),
  (3,	'Valiana',	3,	'female',	3,	NULL,	2),
  (4,	'Alinia',	3,	'female',	4,	NULL,	3),
  (5,	'Eldan',	1,	'male',	1,	NULL,	5),
  (6,	'Remus',	2,	'male',	1,	NULL,	8),
  (7,	'Celia',	3,	'female',	2,	NULL,	8),
  (8,	'Salazar',	2,	'male',	3,	NULL,	9),
  (9,	'Durhana',	4,	'female',	2,	NULL,	9),
  (10,	'Il-Salah',	1,	'male',	4,	NULL,	8),
  (11,	'Murie',	1,	'female',	1,	NULL,	10),
  (12,	'Tharna',	4,	'female',	3,	NULL,	12),
  (13,	'Skur-dah',	5,	'male',	1,	NULL,	12),
  (14,	'Lucia',	3,	'female',	5,	NULL,	5),
  (15,	'Amadeus',	2,	'male',	5,	NULL,	10),
  (16,	'Ra-dah',	5,	'male',	2,	NULL,	14),
  (17,	'Ignatius',	2,	'male',	4,	NULL,	14),
  (18,	'Albus',	2,	'male',	1,	2,	16),
  (19,	'Il-Maval',	1,	'male',	1,	1,	16),
  (20,	'Elena',	3,	'female',	3,	7,	16),
  (21,	'Ilvis',	2,	'male',	3,	8,	18),
  (22,	'Avia',	3,	'female',	4,	5,	18),
  (23,	'Sar-dah',	5,	'male',	2,	3,	19),
  (24,	'Ilrin',	4,	'male',	2,	4,	19),
  (25,	'Thordan',	4,	'male',	6,	NULL,	5),
  (26,	'Thalia',	3,	'female',	6,	NULL,	9),
  (27,	'Paul',	2,	'male',	6,	NULL,	14),
  (28,	'Tacitus',	2,	'male',	6,	10,	16),
  (29,	'Aurelia',	2,	'female',	6,	9,	16),
  (30,	'El-Madin',	4,	'male',	5,	NULL,	14),
  (31,	'Erdun',	4,	'male',	6,	10,	19);

INSERT INTO `quests` (id, name, required_level, required_class, required_race, required_quest, required_white_karma, required_dark_karma, conflicts_quest, needed_item, item_amount, item_lose, needed_money, needed_arena_wins, needed_guild_donation, needed_active_skills_level, needed_friends, reward_money, reward_xp, reward_item, reward_white_karma, reward_dark_karma, reward_pet, npc_start, npc_end) VALUES
  (1, 'Moving around', 1, 3, null, null, 0, 0, null, null, 0, 0, 0, 0, 0, 0, 0, 0, 20, null, 0, 0, null, 1, 2),
  (2, 'Moving around', 1, 1, 1, null, 0, 0, null, null, 0, 0, 0, 0, 0, 0, 0, 0, 20, null, 0, 0, null, 3, 4),
  (3, 'Find a book', 1, 3, null, 1, 0, 0, null, 1, 1, 0, 0, 0, 0, 0, 0, 0, 10, null, 0, 0, null, 1, 1),
  (4, 'Get your equipment', 1, 3, null, 3, 0, 0, null, 6, 1, 0, 0, 0, 0, 0, 0, 0, 5, 10, 0, 0, null, 1, 1),
  (5, 'Find a book', 1, 1, 1, 2, 0, 0, null, 33, 1, 0, 0, 0, 0, 0, 0, 0, 10, null, 0, 0, null, 3, 3),
  (6, 'Get your equipment', 1, 1, 1, 5, 0, 0, null, 2, 1, 0, 0, 0, 0, 0, 0, 0, 5, 8, 0, 0, null, 3, 3),
  (7, 'Earning money', 1, 3, null, 4, 0, 0, null, null, 0, 0, 0, 2, 0, 0, 0, 5, 30, null, 0, 0, null, 1, 1),
  (8, 'New helper', 1, 3, null, 7, 0, 0, null, 71, 1, 1, 0, 0, 0, 0, 0, 15, 20, null, 0, 0, null, 5, 5),
  (9, 'Storehouse', 1, 3, null, 8, 0, 0, null, 72, 2, 1, 0, 0, 0, 0, 0, 10, 30, null, 0, 0, null, 5, 5),
  (10, 'Guild', 3, 3, null, 9, 0, 0, null, null, 0, 0, 0, 0, 10, 0, 0, 0, 30, null, 0, 0, null, 1, 1),
  (11, 'Skills', 3, 3, null, 10, 0, 0, null, null, 0, 0, 0, 0, 0, 2, 0, 0, 30, null, 0, 0, null, 1, 1),
  (12, 'Hour of glory', 3, 3, null, 11, 0, 0, null, null, 0, 0, 0, 5, 0, 0, 0, 15, 40, null, 0, 0, null, 1, 1),
  (13, 'Friends', 4, 3, null, 12, 0, 0, null, null, 0, 0, 0, 0, 0, 0, 2, 0, 20, null, 0, 0, null, 1, 1),
  (14, 'Karma', 4, 3, null, 13, 0, 0, null, null, 0, 0, 0, 0, 0, 0, 0, 0, 20, null, 0, 0, null, 1, 1),
  (15, 'White karma', 1, 3, null, 14, 0, 0, 16, null, 0, 0, 0, 0, 5, 0, 0, 0, 30, null, 1, 0, null, 1, 1),
  (16, 'Dark karma', 1, 3, null, 14, 0, 0, 15, null, 0, 0, 5, 0, 0, 0, 0, 0, 30, null, 0, 1, null, 1, 1),
  (17, 'Final exam', 4, 3, null, 14, 0, 0, null, null, 0, 0, 0, 5, 0, 4, 0, 10, 40, 73, 0, 0, null, 1, 1),
  (18, 'Graduation', 5, 3, null, 17, 0, 0, null, 73, 1, 0, 0, 0, 0, 0, 0, 10, 30, 74, 0, 0, null, 1, 1),
  (19, 'Broken sword', 5, null, null, null, 1, 0, 20, 15, 1, 1, 0, 0, 0, 0, 0, 18, 25, null, 1, 0, null, 7, 7),
  (20, 'Broken crossbow', 5, null, null, null, 0, 1, 19, 20, 1, 1, 0, 0, 0, 0, 0, 22, 25, null, 0, 1, null, 9, 9),
  (21, 'Troubles in port', 5, null, null, 19, 1, 0, null, null, 0, 0, 0, 3, 0, 0, 0, 30, 40, null, 1, 0, null, 7, 7),
  (22, 'Troubles with guards', 5, null, null, 20, 0, 1, null, null, 0, 0, 0, 3, 0, 0, 0, 30, 40, null, 0, 1, null, 9, 9),
  (23, 'Guard', 5, null, null, 21, 1, 0, null, null, 0, 0, 0, 0, 0, 0, 2, 10, 20, null, 0, 0, null, 7, 7),
  (24, 'Underworld', 5, null, null, 22, 0, 1, null, null, 0, 0, 0, 0, 0, 0, 2, 10, 20, null, 0, 0, null, 9, 9),
  (25, 'New recruits', 6, null, null, 23, 2, 0, null, null, 0, 0, 0, 3, 0, 5, 0, 15, 35, null, 1, 0, null, 10, 10),
  (26, 'New recruits', 6, null, null, 24, 0, 2, null, null, 0, 0, 0, 3, 0, 5, 0, 15, 35, null, 0, 1, null, 9, 9),
  (27, 'Big raid', 6, null, null, 25, 4, 0, null, 16, 2, 1, 0, 6, 0, 0, 0, 30, 50, 31, 2, 0, null, 10, 10),
  (28, 'Grand theft', 6, null, null, 26, 0, 4, null, 17, 2, 1, 0, 6, 0, 0, 0, 30, 50, 31, 0, 2, null, 9, 9);

INSERT INTO `quest_areas` (`id`, `name`, `required_level`, `required_race`, `required_class`, `pos_x`, `pos_y`, `entry_stage`) VALUES
(1,	'Academy of Magic',	0,	NULL,	3,	250,	35,	2),
(2,	'Sands of Ramir - northwest',	0,	1,	NULL,	246,	153,	4),
(3,	'Border woods',	25,	NULL,	NULL,	80,	165,	NULL),
(4,	'North Great Horde',	30,	NULL,	NULL,	205,	109,	NULL),
(5, 'Sands of Ramir - coastline', 5, NULL, NULL, 305, 190, 10),
(6, 'Sands of Ramir - borderlands', 20, NULL, NULL, 280, 168, NULL),
(7, 'Southeast Great Horde', 25, NULL, NULL, 264, 135, NULL);

INSERT INTO `quest_stages` (`id`, `name`, `required_level`, `required_race`, `required_class`, `area`, `pos_x`, `pos_y`) VALUES
  (1,	'Study Room',	0,	NULL,	3,	1,	215,	65),
  (2,	'Hall',	1,	NULL,	3,	1,	115,	215),
  (3,	'Library',	1,	NULL,	3,	1,	119,	37),
  (4,	'Village 1 - Village Square',	0,	1,	NULL,	2,	90,	60),
  (5,	'Village 1 - Smithy',	1,	1,	NULL,	2,	60,	80),
  (6,	'Infirmary',	1,	NULL,	3,	1,	60,	62),
  (7,	'Village 1 - Alchemist\'s hut',	1,	1,	NULL,	2,	55,	18),
  (8,	'Storehouse',	3,	NULL,	3,	1,	187,	180),
  (9,	'Village 1 - General store',	1,	1,	NULL,	2,	120,	100),
  (10, 'Port', 5, NULL, NULL, 5, 215, 23),
  (11, 'Town of Aldun', 5, NULL, NULL, 5, 188, 35);

INSERT INTO `routes_stages` (`id`, `from`, `to`) VALUES
  (1,	1,	2),
  (2,	1,	3),
  (3,	2,	3),
  (4,	4,	5),
  (5,	1,	6),
  (6,	2,	6),
  (7,	4,	7),
  (8,	1,	8),
  (9,	2,	8),
  (10,	4,	9),
  (11,	10,	11);

INSERT INTO `routes_areas` (`id`, `from`, `to`) VALUES
  (1, 1, 5),
  (2, 2, 5),
  (3, 5, 6),
  (4, 6, 7),
  (5, 2, 6),
  (6, 4, 7);

INSERT INTO `shop_items` (`id`, `npc`, `item`, `order`) VALUES
  (1,	2,	1,	1),
  (2,	2,	6,	3),
  (3,	2,	10,	4),
  (4,	2,	21,	5),
  (5,	2,	25,	6),
  (6,	4,	33,	1),
  (7,	4,	2,	2),
  (8,	4,	8,	3),
  (10,	4,	15,	4),
  (11,	4,	19,	5),
  (12,	4,	23,	6),
  (13,  2,  71, 2),
  (14,  6,  72, 1),
  (15, 8, 15, 1),
  (16, 8, 16, 2),
  (17, 8, 19, 3),
  (18, 8, 30, 4),
  (19, 8, 17, 5),
  (20, 8, 18, 6),
  (21, 8, 20, 7),
  (22, 8, 21, 8),
  (23, 8, 22, 9),
  (24, 8, 23, 10),
  (25, 8, 24, 11),
  (26, 8, 25, 12),
  (27, 8, 26, 13);

INSERT INTO `skills_attacks` (`id`, `name`, `needed_class`, `needed_specialization`, `needed_level`, `base_damage`, `damage_growth`, `levels`, `target`, `strikes`, `hit_rate`) VALUES
(1,	'Assault',	1,	NULL,	1,	'110%',	'5%',	5,	'single',	1,	NULL),
(2,	'Shadow strike',	2,	NULL,	1,	'61%',	'2%',	5,	'single',	2,	NULL),
(3,	'Blast',	3,	NULL,	1,	'115%',	'5%',	5,	'single',	1,	NULL),
(4,	'Rain of arrows',	4,	NULL,	1,	'35%',	'2%',	5,	'single',	3,	NULL),
(5,	'Sound wave',	5,	NULL,	1,	'80%',	'2%',	5,	'row',	1,	NULL),
(6,	'Wrath strike',	1,	1,	15,	'100%',	'5%',	5,	'row',	1,	'80%'),
(7,	'Light beam',	1,	2,	15,	'40%',	'3%',	5,	'column',	2,	NULL),
(8,	'Execution',	2,	3,	15,	'140%',	'5%',	5,	'single',	1,	'120%'),
(9,	'Gas leakage',	2,	4,	15,	'70%',	'5%',	5,	'row',	1,	NULL),
(10,	'Master shot',	4,	5,	15,	'90%',	'4%',	5,	'row',	1,	'120%'),
(11,	'Double shot',	4,	6,	15,	'100%',	'4%',	5,	'column',	1,	NULL),
(12,	'Fireball',	3,	7,	15,	'60%',	'5%',	5,	'single',	2,	NULL),
(13,	'Freezing point',	3,	8,	15,	'50%',	'3%',	5,	'column',	2,	'120%'),
(14,	'Touch of faith',	6,	NULL,	1,	'90%',	'4%',	5,	'column',	1,	NULL),
(15,	'Holy fire',	6,	9,	15,	'70%',	'5%',	5,	'row',	1,	NULL),
(16,	'Ten fists',	6,	10,	15,	'35%',	'2%',	5,	'single',	3,	'120%'),
(17,	'Long verse',	5,	11,	15,	'55%',	'1%',	5,	'column',	2,	NULL),
(18,	'Rapture',	5,	12,	15,	'100%',	'3%',	5,	'single',	1,	'140%');

INSERT INTO `skills_specials` (`id`, `name`, `needed_class`, `needed_specialization`, `needed_level`, `type`, `target`, `stat`, `value`, `value_growth`, `levels`, `duration`) VALUES
(1,	'Shield',	1,	NULL,	1,	'buff',	'self',	'defense',	12,	2,	5,	3),
(2,	'Shadow protection',	2,	NULL,	1,	'buff',	'self',	'dodge',	15,	2,	5,	3),
(3,	'Quicken spell',	3,	NULL,	1,	'buff',	'self',	'initiative',	18,	2,	5,	3),
(4,	'Precision',	4,	NULL,	1,	'buff',	'self',	'hit',	15,	2,	5,	3),
(5,	'Cover',	1,	NULL,	10,	'buff',	'party',	'defense',	14,	1,	6,	4),
(6,	'Smoke',	2,	NULL,	10,	'debuff',	'enemy_party',	'hit',	14,	1,	6,	4),
(7,	'Entangle',	3,	NULL,	10,	'debuff',	'enemy',	'initiative',	13,	2,	6,	4),
(8,	'Evasion',	4,	NULL,	10,	'buff',	'party',	'dodge',	10,	1,	6,	4),
(9,	'Irksome noise',	5,	NULL,	1,	'debuff',	'enemy',	'damage',	15,	1,	5,	3),
(10,	'Song of agility',	5,	NULL,	10,	'buff',	'party',	'dodge',	15,	2,	6,	4),
(11,	'Rage',	1,	1,	15,	'buff',	'self',	'damage',	20,	3,	6,	3),
(12,	'Gaia\'s protection',	1,	2,	15,	'buff',	'party',	'maxHitpoints',	15,	3,	6,	4),
(13,	'Mark of death',	2,	3,	15,	'debuff',	'enemy',	'dodge',	15,	4,	6,	2),
(14,	'Stunning strike',	2,	4,	15,	'debuff',	'enemy',	'initiative',	15,	4,	6,	2),
(15,	'Poisoned arrow',	4,	5,	15,	'poison',	'enemy',	NULL,	3,	1,	6,	4),
(16,	'Marksman',	4,	6,	15,	'buff',	'party',	'hit',	15,	3,	6,	3),
(17,	'Weakness',	3,	7,	15,	'debuff',	'enemy',	'defense',	15,	4,	6,	2),
(18,	'Magic shield',	3,	8,	15,	'buff',	'self',	'defense',	10,	4,	6,	3),
(19,	'Power of confidence',	6,	NULL,	1,	'buff',	'self',	'damage',	15,	2,	5,	2),
(20,	'Spirit crusher',	6,	NULL,	10,	'debuff',	'enemy_party',	'damage',	14,	1,	6,	3),
(21,	'Song of faith',	6,	9,	15,	'buff',	'party',	'damage',	15,	3,	6,	3),
(22,	'Turtle\'s shell',	6,	10,	15,	'buff',	'self',	'defense',	15,	3,	6,	3),
(23,	'Sonic speed',	5,	11,	15,	'buff',	'party',	'initiative',	15,	3,	6,	2),
(24,	'Trance',	5,	12,	15,	'debuff',	'enemy',	'defense',	15,	3,	6,	3);

INSERT INTO `npcs` (`id`, `name`, `race`, `class`, `specialization`, `fight`, `smith`, `sprite`, `portrait`, `stage`, `karma`, `personality`, `level`, `pos_x`, `pos_y`) VALUES
  (1,	'Mentor',	2,	3,	NULL,	0,	0,	'mentor.jpeg',	'mentor.jpeg',	1,	'neutral',	'teaching',	10,	1,	1),
  (2,	'Librarian',	2,	3,	NULL,	0,	1,	'librarian.jpeg',	'librarian.jpeg',	3,	'neutral',	'friendly',	10,	1,	1),
  (3,	'Instructor',	1,	1,	NULL,	0,	0,	'instructor.jpeg',	'instructor.jpeg',	4,	'neutral',	'teaching',	10,	1,	1),
  (4,	'Blacksmith',	1,	1,	NULL,	0,	1,	'blacksmith.jpeg',	'blacksmith.jpeg',	5,	'neutral',	'friendly',	10,	1,	1),
  (5,	'Nurse',	2,	6,	NULL,	0,	0,	'nurse.jpeg',	'nurse.jpeg',	6,	'white',	'friendly',	5,	1,	1),
  (6,	'Storeman',	1,	1,	NULL,	0,	0,	'storeman.jpeg',	'storeman.jpeg',	8,	'neutral',	'reserved',	5,	1,	1),
  (7,   'Port manager', 1, 1, 1, 0, 0, 'port-manager.jpeg', 'port-manager.jpeg', 10, 'white', 'friendly', 15, 1, 1),
  (8,   'Blacksmith', 1, 1, NULL, 0, 1, 'blacksmith.jpeg', 'blacksmith.jpeg', 11, 'neutral', 'friendly', 10, 1, 1),
  (9,   'Shady person', 1, 2, NULL, 0, 0, 'shady-person.jpeg', 'shady-person.jpeg', 10, 'dark', 'crazy', 12, 1, 1),
  (10,  'Guard', 1, 1, NULL, 0, 0, 'barbarian-guard.jpeg', 'barbarian-guard.jpeg', 10, 'white', 'friendly', 12, 1, 1);

INSERT INTO `pve_arena_opponent_equipment` (`id`, `npc`, `item`) VALUES
(1,	2,	3),
(2,	2,	12),
(3,	5,	13),
(4,	7,	18),
(5,	9,	20),
(6,	6,	19),
(7,	8,	28),
(8,	6,	27),
(9,	6,	29),
(10,	8,	14),
(11,	11,	30),
(12,	11,	8),
(13,	8,	31),
(14,	12,	31),
(15,	13,	14),
(16,	13,	16),
(17,	13,	29),
(18,	15,	37),
(19,	17,	31),
(20,	16,	14),
(21,	16,	20),
(22,	18,	42),
(23,	18,	43),
(24,	18,	40),
(25,	20,	49),
(26,	21,	51),
(27,	24,	51),
(28,	23,	58),
(29,	24,	61),
(30,	26,	14),
(31,	27,	31);
