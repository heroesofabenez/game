SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `race` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `occupation` int(11) NOT NULL,
  `specialization` int(11) DEFAULT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  `money` int(6) NOT NULL DEFAULT '0',
  `experience` int(5) NOT NULL DEFAULT '0',
  `strength` float NOT NULL,
  `dexterity` float NOT NULL,
  `constitution` float NOT NULL,
  `intelligence` float NOT NULL,
  `charisma` float NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `guild` int(11) DEFAULT NULL,
  `guildrank` int(1) DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `current_stage` int(11) DEFAULT NULL,
  `white_karma` int(2) DEFAULT '0',
  `dark_karma` int(2) DEFAULT '0',
  `intro` int(2) DEFAULT '1',
  `joined` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `stat_points` float DEFAULT '0',
  `skill_points` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `current_stage` (`current_stage`),
  KEY `occupation` (`occupation`),
  KEY `race` (`race`),
  KEY `guild_rank` (`guildrank`),
  KEY `specialization` (`specialization`),
  KEY `guild` (`guild`),
  CONSTRAINT `characters_ibfk_12` FOREIGN KEY (`guildrank`) REFERENCES `guild_ranks` (`id`),
  CONSTRAINT `characters_ibfk_13` FOREIGN KEY (`specialization`) REFERENCES `character_specializations` (`id`),
  CONSTRAINT `characters_ibfk_14` FOREIGN KEY (`guild`) REFERENCES `guilds` (`id`),
  CONSTRAINT `characters_ibfk_15` FOREIGN KEY (`current_stage`) REFERENCES `quest_stages` (`id`),
  CONSTRAINT `characters_ibfk_6` FOREIGN KEY (`race`) REFERENCES `character_races` (`id`),
  CONSTRAINT `characters_ibfk_7` FOREIGN KEY (`occupation`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `characters_ibfk_9` FOREIGN KEY (`race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `character_attack_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `level` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  KEY `skill` (`skill`),
  CONSTRAINT `character_attack_skills_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`),
  CONSTRAINT `character_attack_skills_ibfk_2` FOREIGN KEY (`skill`) REFERENCES `skills_attacks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `character_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(7) NOT NULL,
  `strength` int(2) NOT NULL,
  `strength_grow` float NOT NULL,
  `dexterity` int(2) NOT NULL,
  `dexterity_grow` float NOT NULL,
  `constitution` int(2) NOT NULL,
  `constitution_grow` float NOT NULL,
  `intelligence` int(2) NOT NULL,
  `intelligence_grow` float NOT NULL,
  `charisma` int(2) NOT NULL,
  `charisma_grow` float NOT NULL,
  `stat_points_level` float NOT NULL,
  `initiative` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `character_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '1',
  `worn` int(1) NOT NULL,
  `durability` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `character_item` (`character`,`item`),
  KEY `item` (`item`),
  CONSTRAINT `character_items_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`),
  CONSTRAINT `character_items_ibfk_2` FOREIGN KEY (`item`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `character_quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `quest` int(11) NOT NULL,
  `progress` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  KEY `quest` (`quest`),
  CONSTRAINT `character_quests_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`),
  CONSTRAINT `character_quests_ibfk_2` FOREIGN KEY (`quest`) REFERENCES `quests` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `character_races` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `strength` int(2) NOT NULL DEFAULT '0',
  `dexterity` int(2) NOT NULL DEFAULT '0',
  `constitution` int(2) NOT NULL DEFAULT '0',
  `intelligence` int(2) NOT NULL DEFAULT '0',
  `charisma` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `character_specializations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `class` int(11) NOT NULL,
  `strength_grow` float NOT NULL,
  `dexterity_grow` float NOT NULL,
  `constitution_grow` float NOT NULL,
  `intelligence_grow` float NOT NULL,
  `charisma_grow` float NOT NULL,
  `stat_points_level` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class` (`class`),
  CONSTRAINT `character_specializations_ibfk_1` FOREIGN KEY (`class`) REFERENCES `character_classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `character_special_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `level` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  KEY `skill` (`skill`),
  CONSTRAINT `character_special_skills_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`),
  CONSTRAINT `character_special_skills_ibfk_2` FOREIGN KEY (`skill`) REFERENCES `skills_specials` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `chat_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `till` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reason` text COLLATE utf8_unicode_ci NOT NULL,
  `revoken` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  CONSTRAINT `chat_bans_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `character` int(11) NOT NULL,
  `area` int(11) DEFAULT NULL,
  `stage` int(11) DEFAULT NULL,
  `guild` int(11) DEFAULT NULL,
  `when` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  KEY `area` (`area`),
  KEY `stage` (`stage`),
  KEY `guild` (`guild`),
  CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`),
  CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`area`) REFERENCES `quest_areas` (`id`),
  CONSTRAINT `chat_messages_ibfk_3` FOREIGN KEY (`stage`) REFERENCES `quest_stages` (`id`),
  CONSTRAINT `chat_messages_ibfk_4` FOREIGN KEY (`guild`) REFERENCES `guilds` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `combats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` longtext NOT NULL,
  `when` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `guilds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `money` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `guild_privileges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `rank` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`action`),
  KEY `rank` (`rank`),
  CONSTRAINT `guild_privileges_ibfk_1` FOREIGN KEY (`rank`) REFERENCES `guild_ranks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `guild_ranks` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `guild_ranks_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guild` int(11) NOT NULL,
  `rank` int(1) NOT NULL,
  `name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rank` (`rank`),
  KEY `guild` (`guild`),
  CONSTRAINT `guild_ranks_custom_ibfk_8` FOREIGN KEY (`rank`) REFERENCES `guild_ranks` (`id`),
  CONSTRAINT `guild_ranks_custom_ibfk_9` FOREIGN KEY (`guild`) REFERENCES `guilds` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `introduction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `race` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `part` int(1) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `race` (`race`),
  KEY `class` (`class`),
  CONSTRAINT `introduction_ibfk_1` FOREIGN KEY (`race`) REFERENCES `character_races` (`id`),
  CONSTRAINT `introduction_ibfk_2` FOREIGN KEY (`class`) REFERENCES `character_classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `slot` enum('weapon','armor','shield','amulet', 'helmet', 'ring', 'item') NOT NULL,
  `type` enum('sword','axe','club','dagger','spear','staff','bow','crossbow','throwing knife') DEFAULT NULL,
  `required_level` int(11) NOT NULL DEFAULT '1',
  `required_class` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `strength` int(3) NOT NULL DEFAULT '0',
  `durability` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `required_class` (`required_class`),
  CONSTRAINT `equipment_ibfk_1` FOREIGN KEY (`required_class`) REFERENCES `character_classes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `subject` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from`) REFERENCES `characters` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to`) REFERENCES `characters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `npcs` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `race` int(11) NOT NULL,
  `quests` int(1) NOT NULL DEFAULT '0',
  `shop` int(1) NOT NULL DEFAULT '0',
  `fight` int(1) NOT NULL DEFAULT '0',
  `smith` int(1) NOT NULL DEFAULT '0',
  `sprite` varchar(35) NOT NULL,
  `portrait` varchar(35) NOT NULL,
  `stage` int(11) NOT NULL,
  `karma` enum('white','neutral','dark') NOT NULL,
  `personality` enum('friendly','crazy', 'shy', 'hostile', 'reserved', 'elitist', 'teaching', 'racist', 'misogynist') NOT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  `pos_x` int(3) NOT NULL,
  `pos_y` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stage` (`stage`),
  KEY `race` (`race`),
  CONSTRAINT `npcs_ibfk_1` FOREIGN KEY (`stage`) REFERENCES `quest_stages` (`id`),
  CONSTRAINT `npcs_ibfk_2` FOREIGN KEY (`race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(2) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `deployed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `owner` (`owner`),
  CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`type`) REFERENCES `pet_types` (`id`),
  CONSTRAINT `pets_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `characters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pet_types` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `bonus_stat` enum('strength','dexterity','constitution','intelligence') NOT NULL,
  `bonus_value` int(2) NOT NULL,
  `image` varchar(50) NOT NULL,
  `required_level` int(2) NOT NULL,
  `required_class` int(11) DEFAULT NULL,
  `required_race` int(11) DEFAULT NULL,
  `cost` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `required_class` (`required_class`),
  KEY `required_race` (`required_race`),
  CONSTRAINT `pet_types_ibfk_1` FOREIGN KEY (`required_class`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `pet_types_ibfk_2` FOREIGN KEY (`required_race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pve_arena_opponents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `race` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `occupation` int(11) NOT NULL,
  `level` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `occupation` (`occupation`),
  KEY `race` (`race`),
  CONSTRAINT `pve_arena_opponents_ibfk_1` FOREIGN KEY (`occupation`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `pve_arena_opponents_ibfk_2` FOREIGN KEY (`race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `quests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `cost_money` int(3) NOT NULL DEFAULT '0',
  `needed_item` int(11) DEFAULT NULL,
  `needed_quest` int(3) DEFAULT NULL,
  `needed_level` int(3) NOT NULL DEFAULT '1',
  `item_amount` int(1) NOT NULL DEFAULT '1',
  `item_lose` int(1) NOT NULL DEFAULT '1',
  `reward_money` int(4) NOT NULL,
  `reward_xp` int(4) NOT NULL,
  `reward_item` int(11) DEFAULT NULL,
  `reward_white_karma` int(2) NOT NULL DEFAULT '0',
  `reward_dark_karma` int(2) NOT NULL DEFAULT '0',
  `reward_pet` int(2) DEFAULT NULL,
  `npc_start` int(3) NOT NULL,
  `npc_end` int(3) NOT NULL,
  `order` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `npc_start` (`npc_start`),
  KEY `npc_end` (`npc_end`),
  KEY `reward_item1` (`reward_item`),
  CONSTRAINT `quests_ibfk_1` FOREIGN KEY (`npc_start`) REFERENCES `npcs` (`id`),
  CONSTRAINT `quests_ibfk_2` FOREIGN KEY (`npc_end`) REFERENCES `npcs` (`id`),
  CONSTRAINT `quests_ibfk_3` FOREIGN KEY (`reward_item`) REFERENCES `items` (`id`),
  CONSTRAINT `quests_ibfk_4` FOREIGN KEY (`reward_pet`) REFERENCES `pet_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `quest_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `required_level` int(3) NOT NULL DEFAULT '0',
  `required_race` int(11) DEFAULT NULL,
  `required_occupation` int(11) DEFAULT NULL,
  `pos_x` int(3) DEFAULT NULL,
  `pos_y` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `required_occupation` (`required_occupation`),
  KEY `required_race` (`required_race`),
  CONSTRAINT `quest_areas_ibfk_1` FOREIGN KEY (`required_occupation`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `quest_areas_ibfk_2` FOREIGN KEY (`required_race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `quest_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `required_level` int(3) NOT NULL DEFAULT '0',
  `required_race` int(11) DEFAULT NULL,
  `required_occupation` int(11) DEFAULT NULL,
  `area` int(11) NOT NULL,
  `pos_x` int(3) NOT NULL,
  `pos_y` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `area` (`area`),
  KEY `required_occupation` (`required_occupation`),
  KEY `required_race` (`required_race`),
  CONSTRAINT `quest_stages_ibfk_1` FOREIGN KEY (`area`) REFERENCES `quest_areas` (`id`),
  CONSTRAINT `quest_stages_ibfk_2` FOREIGN KEY (`required_occupation`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `quest_stages_ibfk_3` FOREIGN KEY (`required_race`) REFERENCES `character_races` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `type` enum('guild_join','guild_app','group_join','friendship') NOT NULL,
  `sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','accepted','declined') NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `routes_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`),
  CONSTRAINT `routes_areas_ibfk_1` FOREIGN KEY (`from`) REFERENCES `quest_areas` (`id`),
  CONSTRAINT `routes_areas_ibfk_2` FOREIGN KEY (`to`) REFERENCES `quest_areas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `routes_stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `from_to` (`from`,`to`),
  KEY `to` (`to`),
  CONSTRAINT `routes_stages_ibfk_1` FOREIGN KEY (`from`) REFERENCES `quest_stages` (`id`),
  CONSTRAINT `routes_stages_ibfk_2` FOREIGN KEY (`to`) REFERENCES `quest_stages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `npc` int(3) NOT NULL,
  `item` int(11) NOT NULL,
  `order` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `npc` (`npc`),
  KEY `item` (`item`),
  CONSTRAINT `shop_items_ibfk_1` FOREIGN KEY (`npc`) REFERENCES `npcs` (`id`),
  CONSTRAINT `shop_items_ibfk_2` FOREIGN KEY (`item`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `skills_attacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `needed_class` int(11) NOT NULL,
  `needed_specialization` int(11) DEFAULT NULL,
  `needed_level` int(2) NOT NULL,
  `base_damage` varchar(30) NOT NULL,
  `damage_growth` varchar(8) NOT NULL,
  `levels` int(2) NOT NULL,
  `target` enum('single','row','column') NOT NULL DEFAULT 'single',
  `strikes` int(1) NOT NULL DEFAULT '1',
  `hit_rate` varchar(30) DEFAULT 'NULL',
  PRIMARY KEY (`id`),
  KEY `needed_class` (`needed_class`),
  KEY `needed_specialization` (`needed_specialization`),
  CONSTRAINT `skills_attacks_ibfk_1` FOREIGN KEY (`needed_class`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `skills_attacks_ibfk_2` FOREIGN KEY (`needed_specialization`) REFERENCES `character_specializations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `skills_specials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `needed_class` int(11) NOT NULL,
  `needed_specialization` int(11) DEFAULT NULL,
  `needed_level` int(2) NOT NULL,
  `type` enum('buff','debuff','stun', 'poison') NOT NULL,
  `target` enum('self','enemy','party','enemy_party') NOT NULL,
  `stat` enum('maxHitpoints','damage','defense','hit','dodge','initiative') DEFAULT NULL,
  `value` int(2) NOT NULL DEFAULT '0',
  `value_growth` int(2) NOT NULL,
  `levels` int(2) NOT NULL,
  `duration` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `needed_class` (`needed_class`),
  KEY `needed_specialization` (`needed_specialization`),
  CONSTRAINT `skills_specials_ibfk_1` FOREIGN KEY (`needed_class`) REFERENCES `character_classes` (`id`),
  CONSTRAINT `skills_specials_ibfk_2` FOREIGN KEY (`needed_specialization`) REFERENCES `character_specializations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `arena_fights_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `day` varchar(10) NOT NULL,
  `amount` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `character` (`character`),
  CONSTRAINT `arena_fights_count_ibfk_1` FOREIGN KEY (`character`) REFERENCES `characters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pve_arena_opponent_equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `npc` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `npc` (`npc`),
  KEY `item` (`item`),
  CONSTRAINT `pve_arena_opponent_equipment_ibfk_1` FOREIGN KEY (`npc`) REFERENCES `pve_arena_opponents` (`id`),
  CONSTRAINT `pve_arena_opponent_equipment_ibfk_2` FOREIGN KEY (`item`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
