<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Addons\Authenticator_Discord;

use NF\NeoFrag\Addons\Authenticator;

class Authenticator_Discord extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Discord',
			'icon'    => 'fa-commenting-o',
			'color'   => '#7289DA',
			'help'    => 'https://discordapp.com/developers/applications/me#top',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return array_merge(parent::config(), [
			'scope' => ['identify email']
		]);
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['code']) && !empty($_GET['state']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->id,
					'username'      => $data->username,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => 'https://cdn.discordapp.com/avatars/'.$data->id.'/'.$data->avatar.'.png?size=250'
				];
			};
		}
	}
}
