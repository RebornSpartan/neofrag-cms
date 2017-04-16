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
	}
}
