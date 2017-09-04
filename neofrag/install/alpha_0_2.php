<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2 extends Install
{
	public function up()
	{
		$this->db	->where('name', 'error')
					->delete('nf_settings_addons');

		$this->db->insert('nf_settings_addons', [
			'name' => 'admin',
			'type' => 'theme'
		]);

		//Addon
		$this->db	->execute('CREATE TABLE `nf_addon_type` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('INSERT INTO `nf_addon_type` (`id`, `name`) VALUES
						(1, \'module\'),
						(2, \'theme\'),
						(3, \'widget\'),
						(4, \'language\'),
						(5, \'authenticator\')')
					->execute('CREATE TABLE `nf_addon` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `type_id` int(11) unsigned DEFAULT NULL,
					  `name` varchar(100) NOT NULL,
					  `data` text,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`,`type_id`),
					  KEY `type_id` (`type_id`),
					  CONSTRAINT `nf_addon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_addon_type` (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8')
					->execute('INSERT INTO `nf_addon` VALUES(NULL, NULL, \'authenticator\', NULL)');

		$types = [
			'module'   => 1,
			'theme'    => 2,
			'widget'   => 3
		];

		foreach ($this->db->from('nf_settings_addons')->order_by('type', 'name')->get() as $addon)
		{
			$this->db->insert('nf_addon', [
				'type_id' => $types[$addon['type']],
				'name'    => $addon['name']
			]);
		}

		foreach ($this->db->from('nf_settings_languages')->get() as $lang)
		{
			$this->db->insert('nf_addon', [
				'type_id' => 4,
				'name'    => $lang['code'],
				'data'    => serialize([
					'order' => $lang['order']
				])
			]);
		}

		foreach ($this->db->from('nf_settings_authenticators')->get() as $auth)
		{
			$this->db->insert('nf_addon', [
				'type_id' => 5,
				'name'    => $auth['name'],
				'data'    => serialize(array_merge(unserialize($auth['settings']), [
					'order'   => $auth['order'],
					'enabled' => $auth['is_enabled']
				]))
			]);
		}

		$this->db	->execute('DROP TABLE nf_settings_addons')
					->execute('DROP TABLE nf_settings_authenticators')
					->execute('DROP TABLE nf_settings_languages')
					->execute('DROP TABLE nf_settings_smileys');

		//Dispositions
		$dispositions = [
			'O:3:"Row"'           => 'O:27:"NF\\NeoFrag\\Displayables\\Row"',
			'O:3:"Col"'           => 'O:27:"NF\\NeoFrag\\Displayables\\Col"',
			'O:12:"Panel_widget"' => 'O:34:"NF\\NeoFrag\\Libraries\\Panels\\Widget"'
		];

		foreach ($this->db->from('nf_dispositions')->get() as $disposition)
		{
			$this->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => "O:27:\"NF\\NeoFrag\\Libraries\\Array_\":1:{s:9:\"\0*\0_array\";".str_replace(array_keys($dispositions), array_values($dispositions), $disposition['disposition']).'}'
						]);
		}

		//I18n
		$this->db->execute('CREATE TABLE `nf_i18n` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `lang_id` int(10) unsigned NOT NULL,
		  `model` varchar(100) DEFAULT NULL,
		  `model_id` int(10) unsigned DEFAULT NULL,
		  `name` varchar(100) NOT NULL,
		  `value` text NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `lang_id` (`lang_id`,`model`,`model_id`,`name`) USING BTREE,
		  KEY `lang_id_2` (`lang_id`),
		  KEY `model` (`model`),
		  KEY `model_id` (`model_id`),
		  KEY `name` (`name`),
		  CONSTRAINT `nf_i18n_ibfk_1` FOREIGN KEY (`lang_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Log I18n
		$this->db->execute('CREATE TABLE `nf_log_i18n` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `language` char(2) NOT NULL,
		  `key` char(32) NOT NULL,
		  `locale` text NOT NULL,
		  `file` varchar(100) NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `language` (`language`,`key`,`file`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Log DB
		$this->db->execute('CREATE TABLE `nf_log_db` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  `action` enum(\'0\',\'1\',\'2\') NOT NULL,
		  `model` varchar(100) NOT NULL,
		  `primaries` varchar(100) DEFAULT NULL,
		  `data` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		//Sessions
		$this->db	->execute('RENAME TABLE `nf_sessions` TO `nf_session`')
					->execute('ALTER TABLE `nf_session` CHANGE `session_id` `id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_session` DROP `ip_address`, DROP `host_name`, DROP `is_crawler`')
					->execute('ALTER TABLE `nf_session` CHANGE `remember_me` `remember` ENUM(\'0\',\'1\') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'0\' AFTER user_id')
					->execute('ALTER TABLE `nf_session` CHANGE `user_data` `data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_session` ADD PRIMARY KEY(`id`)')
					->execute('ALTER TABLE `nf_session` DROP INDEX session_id');

		//Sessions History
		$this->db	->execute('RENAME TABLE `nf_sessions_history` TO `nf_session_history`')
					->execute('ALTER TABLE `nf_session_history` DROP `session_id`')
					->execute('ALTER TABLE nf_session_history DROP FOREIGN KEY nf_sessions_history_ibfk_2')
					->execute('ALTER TABLE nf_session_history DROP INDEX session_id')
					->execute('ALTER TABLE `nf_session_history` DROP `session_id`')
					->execute('ALTER TABLE `nf_session_history` CHANGE `authenticator` `auth` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER user_agent');

		foreach ($this->db->from('nf_session_history')->where('auth <>', '')->get() as $session)
		{
			$this->db	->where('id', $session['id'])
						->update('nf_session_history', [
							'auth' => serialize([
								'authentificator' => $session['auth'],
								'name'            => '',
								'avatar'          => ''
							])
						]);
		}

		//Tracking
		$this->db->execute('CREATE TABLE IF NOT EXISTS `nf_tracking` (
		  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		  `user_id` int(10) UNSIGNED NOT NULL,
		  `model` varchar(100) NOT NULL,
		  `model_id` int(10) UNSIGNED DEFAULT NULL,
		  `date` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `user_id` (`user_id`,`model`,`model_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

		$this->db->where('name', 'nf_debug')->delete('nf_settings');

		$this->db	->where('site', 'default')
					->update('nf_settings', [
						'site' => ''
					]);

		//File
		$this->db	->execute('ALTER TABLE `nf_files` CHANGE `file_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_files` TO `nf_file`');

		//User
		$this->db	->execute('ALTER TABLE `nf_users` CHANGE `user_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_users` TO `nf_user`')
					->execute('ALTER TABLE `nf_user` CHANGE `user_data` `data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('RENAME TABLE `nf_users_profiles` TO `nf_user_profile`');

		$this->db	->execute('RENAME TABLE `nf_users_keys` TO `nf_user_token`')
					->execute('ALTER TABLE `nf_user_token` CHANGE `key_id` `id` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE nf_user_token DROP FOREIGN KEY nf_users_keys_ibfk_2')
					->execute('ALTER TABLE `nf_user_token` DROP `session_id`, DROP `date`')
					->execute('CREATE TABLE `nf_user_auth` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) unsigned NOT NULL,
					  `authenticator_id` int(11) unsigned NOT NULL,
					  `key` varchar(100) NOT NULL,
					  `username` varchar(100) DEFAULT NULL,
					  `avatar` varchar(100) DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `user_id` (`user_id`,`authenticator_id`,`key`),
					  KEY `authenticator_id` (`authenticator_id`),
					  CONSTRAINT `nf_user_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `nf_user_auth_ibfk_2` FOREIGN KEY (`authenticator_id`) REFERENCES `nf_addon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8');

		foreach ($this->db->from('nf_users_auth')->get() as $auth)
		{
			$this->db	->insert('nf_user_auth', [
							'user_id'          => $auth['user_id'],
							'authenticator_id' => $this->db->select('id')->from('nf_addon')->where('name', $auth['authenticator'])->where('type_id', 5)->row(),
							'key'              => $auth['id']
						]);
		}

		$this->db->execute('DROP TABLE nf_users_auth');

		//Comment
		$this->db	->execute('ALTER TABLE `nf_comments` CHANGE `comment_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_comments` TO `nf_comment`');

		//Talks
		$this->db	->execute('ALTER TABLE nf_talks CONVERT TO CHARACTER SET utf8')
					->execute('ALTER TABLE nf_talks_messages CONVERT TO CHARACTER SET utf8');
	}
}
