SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `characters` (`id`, `name`, `race`, `gender`, `class`, `specialization`, `level`, `money`, `experience`, `strength`, `dexterity`, `constitution`, `intelligence`, `charisma`, `guild`, `guildrank`, `owner`, `current_stage`, `white_karma`, `dark_karma`, `intro`, `joined`, `stat_points`, `skill_points`) VALUES
(1,	'James The Invisible',	2,	'male',	3,	NULL,	3,	159,	143,	9,	10.2,	10.2,	14,	11.5,	1,	7,	1,	1,	0,	0,	2,	'2015-05-10 13:42:10',	0.05,	0),
(2,	'Ilivia',	3,	'female',	2,	NULL,	1,	0,	0,	10,	13,	6,	11,	12,	1,	1,	1,	1,	0,	0,	1,	'2015-05-10 13:42:10',	0,	0),
(3,	'Amanda',	2,	'female',	3,	NULL,	1,	0,	0,	9,	10,	9,	12,	11,	2,	7,	0,	1,	0,	0,	2,	'2015-05-10 14:32:22',	0,	0),
(4,	'Alvid',	3,	'male',	1,	NULL,	1,	0,	0,	10,	11,	10,	10,	11,	3,	7,	0,	1,	0,	0,	2,	'2015-05-12 17:47:55',	0,	0);

INSERT INTO `character_items` (`id`, `character`, `item`, `amount`, `worn`, `durability`) VALUES
(1,	1,	6,	1,	1,	10);

INSERT INTO `character_quests` (`id`, `character`, `quest`, `progress`) VALUES
(1,	1,	1,	1);

INSERT INTO `chat_messages` (`id`, `message`, `character`, `area`, `stage`, `guild`, `when`) VALUES
  (1,	'Test message',	1,	NULL,	NULL,	1,	'2015-06-10 16:13:06'),
  (2,	'Hello world!',	1,	NULL,	NULL,	1,	'2015-06-10 16:34:27'),
  (3,	'test',	1,	NULL,	NULL,	1,	'2015-06-10 17:04:36'),
  (4,	'test tests',	1,	NULL,	NULL,	1,	'2015-06-10 18:19:04'),
  (5,	'Hail, fellow wizards',	1,	NULL,	1,	NULL,	'2015-06-10 18:30:04'),
  (6,	'Welcome to Academy of Magic',	1,	1,	NULL,	NULL,	'2015-06-10 22:06:30'),
  (7,	'Hello',	1,	NULL,	1,	NULL,	'2015-06-11 10:24:20');

INSERT INTO `guilds` (`id`, `name`, `description`, `money`) VALUES
(1,	'Dawn',	'.',	0),
(2,	'Wizards College',	'only for wizards',	0),
(3,	'Elven Council',	'only elves',	0);

INSERT INTO `messages` (`id`, `from`, `to`, `subject`, `text`, `sent`, `read`) VALUES
(1,	1,	2,	'Test',	'This is just a test message.',	'2015-06-11 11:29:52',	0),
(2,	1,	3,	'Welcome',	'Welcome to the world of Abenez.',	'2015-09-26 14:47:32',	0),
(3,	1,	1,	'Test',	'Just a test ...',	'2015-09-26 14:53:14',	0);

INSERT INTO `requests` (`id`, `from`, `to`, `type`, `sent`, `status`) VALUES
(1,	2,	1,	'guild_app',	'2015-05-12 14:09:09',	'accepted');

INSERT INTO `character_attack_skills` (`id`, `character`, `skill`, `level`) VALUES
(1,	1,	3,	2);

INSERT INTO `pets` (`id`, `type`, `name`, `owner`, `deployed`) VALUES
(1,	3,	NULL,	1,	1);

INSERT INTO `guild_ranks_custom` (`id`, `guild`, `rank`, `name`) VALUES
  (1,	1,	1,	'Sun observer'),
  (2,	1,	2,	'Sun chaser'),
  (3,	1,	3,	'Sun follower'),
  (4,	1,	4,	'Sun watcher'),
  (5,	1,	5,	'Sun noble'),
  (6,	1,	6,	'Sun prince'),
  (7,	1,	7,	'Sun ruler');
