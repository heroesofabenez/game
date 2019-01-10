SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `character_classes` (`id`, `name`, `strength`, `strength_grow`, `dexterity`, `dexterity_grow`, `constitution`, `constitution_grow`, `intelligence`, `intelligence_grow`, `charisma`, `charisma_grow`, `stat_points_level`, `initiative`, `playable`) VALUES
(1,	'fighter',	11,	0.25,	10,	0.1,	12,	0.5,	9,	0.1,	9,	0.1,	1,	'1d5+DEX/4',	1),
(2,	'rogue',	11,	0.25,	12,	0.5,	8,	0.1,	10,	0.1,	10,	0.1,	1,	'2d3+DEX/4',	1),
(3,	'wizard',	9,	0,	10,	0.1,	9,	0.1,	12,	0.5,	11,	0.25,	1.1,	'5d2+INT/3',	1),
(4,	'archer',	9,	0,	12,	0.5,	10,	0.1,	10,	0.25,	10,	0.1,	1.1,	'4d2+DEX/4',	1),
(5,	'bard',	9,	0,	11,	0.25,	9,	0.1,	10,	0.1,	12,	0.5,	1.1,	'5d2+CHAR/3',	0),
(6,	'preacher',	9,	0,	9,	0,	10,	0.2,	11,	0.25,	12,	0.5,	1.1,	'5d2+INT/3',	0);

INSERT INTO `character_races` (`id`, `name`, `strength`, `dexterity`, `constitution`, `intelligence`, `charisma`, `playable`) VALUES
(1,	'barbarian',	1,	0,	1,	-1,	-1,	1),
(2,	'human',	0,	0,	0,	0,	0,	1),
(3,	'elf',	-1,	1,	-2,	1,	2,	1),
(4,	'dwarf',	1,	-1,	2,	-1,	-1,	1),
(5,	'orc',	1,	1,	0,	-1,	-2,	0);

INSERT INTO `character_specializations` (`id`, `name`, `class`, `strength_grow`, `dexterity_grow`, `constitution_grow`, `intelligence_grow`, `charisma_grow`, `stat_points_level`) VALUES
(1,	'berserker',	1,	0.6,	0.2,	0.4,	0.0,	0.1,	1.2),
(2,	'paladin',	1,	0.2,	0,	0.75,	0.25,	0.3,	1),
(3,	'executioner',	2,	0.25,	0.75,	0.2,	0.2,	0.1,	1),
(4,	'spy',	2,	0.25,	0.6,	0.2,	0.25,	0.2,	1),
(5,	'ranger',	4,	0.1,	0.75,	0.2,	0.35,	0.1,	1),
(6,	'bowman',	4,	0.3,	0.5,	0.3,	0.2,	0.2,	1),
(7,	'sorcerer',	3,	0,	0.2,	0.2,	0.75,	0.35,	1),
(8,	'mystic',	3,	0.1,	0.3,	0.3,	0.6,	0.2,	1),
(9,	'priest',	6,	0,	0,	0.25,	0.5,	0.75,	1),
(10,	'monk',	6,	0,	0,	0.5,	0.75,	0.25,	1);

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
(36,	'Flute',	'weapon',	'instrument',	10,	5,	NULL,	13,	2,	20),
(37,	'Drum',	'weapon',	'instrument',	10,	5,	NULL,	17,	3,	20),
(38,	'Violet shirt',	'armor',	NULL,	10,	5,	NULL,	0,	2,	20),
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
(70,	'Monk\'s Cowl',	'armor',	NULL,	15,	6,	10,	28,	3,	20);

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
(19,	'Royal Hawk',	'dexterity',	20,	'',	45,	4,	NULL,	0);

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
  (24,	'Ilrin',	4,	'male',	2,	4,	19);

INSERT INTO `quests` (`id`, `name`, `required_level`, `required_class`, `required_race`, `required_quest`, `needed_item`, `item_amount`, `item_lose`, `needed_money`, `reward_money`, `reward_xp`, `reward_item`, `reward_white_karma`, `reward_dark_karma`, `reward_pet`, `npc_start`, `npc_end`) VALUES
  (1,	'Find a book',	1,	3,	NULL,	NULL,	1,	1,	0,	0,	0,	10,	NULL,	0,	0,	NULL,	1,	1),
  (2,	'Get your equipment',	1,	3,	NULL,	1,	6,	1,	0,	0,	0,	5,	10,	0,	0,	NULL,	1,	1),
  (3,	'Find a book',	1,	1,	1,	NULL,	33,	1,	0,	0,	0,	10,	NULL,	0,	0,	NULL,	3,	3),
  (4,	'Get your equipment',	1,	1,	1,	3,	2,	1,	0,	0,	0,	5,	8,	0,	0,	NULL,	3,	3);

INSERT INTO `quest_areas` (`id`, `name`, `required_level`, `required_race`, `required_class`, `pos_x`, `pos_y`, `entry_stage`) VALUES
(1,	'Academy of Magic',	0,	NULL,	3,	220,	35,	2),
(2,	'Sands of Ramir - borderlands',	0,	1,	NULL,	220,	153,	4),
(3,	'Border woods',	25,	NULL,	NULL,	80,	165,	NULL),
(4,	'North Great Horde',	30,	NULL,	NULL,	175,	109,	NULL);

INSERT INTO `quest_stages` (`id`, `name`, `required_level`, `required_race`, `required_class`, `area`, `pos_x`, `pos_y`) VALUES
(1,	'Study Room',	0,	NULL,	3,	1,	215,	65),
(2,	'Hall',	1,	NULL,	3,	1,	115,	215),
(3,	'Library',	1,	NULL,	3,	1,	119,	37),
(4,	'Village 1 - Village Square',	0,	1,	NULL,	2,	100,	70),
(5,	'Village 1 - Smithy',	1,	1,	NULL,	2,	70,	90);

INSERT INTO `routes_stages` (`id`, `from`, `to`) VALUES
(1,	1,	2),
(2,	1,	3),
(3,	2,	3),
(4,	4,	5);

INSERT INTO `shop_items` (`id`, `npc`, `item`, `order`) VALUES
  (1,	2,	1,	1),
  (2, 2, 6, 2),
  (3, 2, 10, 3),
  (4,	2,	21,	4),
  (5,	2,	25,	5);

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
(16,	'Ten fists',	6,	10,	15,	'35%',	'2%',	5,	'single',	3,	'120%');

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
(22,	'Turtle\'s shell',	6,	10,	15,	'buff',	'self',	'defense',	15,	3,	6,	3);

INSERT INTO `npcs` (`id`, `name`, `race`, `class`, `specialization`, `fight`, `smith`, `sprite`, `portrait`, `stage`, `karma`, `personality`, `level`, `pos_x`, `pos_y`) VALUES
  (1,	'Mentor',	2,	3,	NULL,	0,	0,	'mentor.jpeg',	'mentor.jpeg',	1,	'neutral',	'teaching',	10,	1,	1),
  (2,	'Librarian',	2,	3,	NULL,	0,	1,	'librarian.jpeg',	'librarian.jpeg',	3,	'neutral',	'friendly',	10,	1,	1),
  (3,	'Instructor',	1,	1,	NULL,	0,	0,	'instructor.jpeg',	'instructor.jpeg',	4,	'neutral',	'teaching',	10,	1,	1),
  (4,	'Blacksmith',	1,	1,	NULL,	0,	1,	'blacksmith.jpeg',	'blacksmith.jpeg',	5,	'neutral',	'friendly',	10,	1,	1);

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
(29,	24,	61);
