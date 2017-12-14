<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Addons;

use NF\NeoFrag\Loadables\Addon;

abstract class Authenticator extends Addon
{
	static public function __class($name)
	{
		return 'Addons\\authenticator_'.$name.'\\authenticator_'.$name;
	}

	static public function __label()
	{
		return ['Authentificateurs', 'Authentificateur', 'fa-lock', 'info'];
	}

	static public function url()
	{
		return (NeoFrag()->url->https ? 'https' : 'http').'://'.NeoFrag()->url->host.NeoFrag()->url->base.'user/auth';
	}

	public function __actions()
	{
		return [
			['enable',   'Activer',       'fa-check',   'success'],
			['disable',  'Désactiver',    'fa-times',   'muted'],
			['settings', 'Configuration', 'fa-wrench',  'warning'],
			NULL,
			['reset',    'Réinitialiser', 'fa-refresh', 'danger'],
			['delete',   'Désinstaller',  'fa-remove',  'danger']
		];
	}

	protected function __info()
	{
		return [];
	}

	protected $_keys = ['id', 'secret'];

	abstract public function data(&$params = []);

	public function is_setup()
	{
		foreach ($this->_keys as $key)
		{
			if (empty($this->settings()->$key))
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	public function admin()
	{
		$settings = [];

		foreach ($this->_keys as $key)
		{
			$settings[$key] = !empty($this->_settings[$key]) ? utf8_htmlentities($this->_settings[$key]) : '';
		}

		return [
			'title'    => icon($this->icon).' '.$this->title,
			'help'     => icon('fa-info-circle').' <a href="'.$this->help.'" target="_blank">'.$this->help.'</a>',
			'settings' => $settings,
			'params'   => $this->_params()
		];
	}

	public function update($settings)
	{
		$this->_settings = [];

		foreach ($this->_keys as $key)
		{
			$this->_settings[$key] = !empty($settings[$key]) ? $settings[$key] : '';
		}

		NeoFrag()->db	->where('name', $this->name)
						->update('nf_settings_authenticators', [
							'settings'   => serialize($this->_settings),
							'is_enabled' => $this->is_setup()
						]);
	}

	public function config()
	{
		return [
			'applicationId'     => $this->settings()->id,
			'applicationSecret' => $this->settings()->secret
		];
	}

	public function settings()
	{
		return $this->__settings->{$this->url->production() ? 'prod' : 'dev'};
	}

	public function __toString()
	{
		$button = $this	->button()
						->tooltip($this->info()->title)
						->icon($this->info()->icon)
						->style('background-color', $this->info()->color)
						->url('user/auth/'.url_title($this->info()->name));

		return '<div class="btn-auth">'.$button.'</div>';
	}

	protected function _params()
	{
		return [
			'callback' => static::url().'/'.url_title($this->info()->name)
		];
	}
}
