<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Loadables;

use NF\NeoFrag\NeoFrag;

abstract class Addon extends NeoFrag implements \NF\NeoFrag\Loadable
{
	static protected $_objects = [];

	abstract protected function __info();
	//abstract public function paths();

	static public function __load($caller, $args = [])
	{
		$name  = array_shift($args);
		$addon = array_shift($args);

		if (!isset(static::$_objects[$class = get_called_class()][$name]))
		{
			$dir = ($type = strtolower(preg_replace('/.+Addons\\\/', '', $class))).'s';

			if (!$addon)
			{
				$addon = NeoFrag()->model2('addon')->get($type, $name, FALSE);
			}

			if ($addon() && (static::$_objects[$class][$name] = $addon = $caller->___load('addons', static::__class($name), [$addon])))
			{
				$addon->__path(function($caller, $type, $file) use ($dir){
					$file = [$file];

					if (!in_array($type, ['addons', 'assets']) && $type)
					{
						array_unshift($file, $type);
					}

					$file = $dir.'/'.$caller->info()->name.'/'.implode('/', $file);

					if (!NEOFRAG_SAFE_MODE)
					{
						yield 'overrides/'.$file;

						if ($theme = $caller->output->theme())
						{
							yield 'themes/'.$theme->info()->name.'/overrides/'.$file;
						}
					}

					yield $file;
				});
			}
		}

		return static::$_objects[$class][$name];
	}

	static public function __class($name)
	{
		return 'Addons\\'.$name.'\\'.$name;
	}

	static public function __label()
	{
		return ['Addons', 'Addon', 'fa-puzzle-piece', 'info'];
	}

	public function __actions()
	{
		return [];
	}

	protected $__info     = [];
	protected $__settings = [];

	public function __construct($addon)
	{
		$this->__info     = [
			'name' => $addon->name
		];

		$this->__settings = (object)$addon->data;

		$this->__addon    = $addon;
	}

	public function info()
	{
		return (object)array_merge($this->__info(), $this->__info);
	}

	public function settings()
	{
		return $this->__settings;
	}

	public function is_enabled()
	{
		return !$this->is_removable() || isset($this->settings()->enabled);
	}

	public function is_deactivatable()
	{
		return !empty(static::$core[$this->__info['name']]) || $this->is_removable();
	}

	public function is_removable()
	{
		return !isset(static::$core) || !isset(static::$core[$this->__info['name']]);
	}

	public function get_title($new_title = NULL)
	{
		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->lang($this->info()->title, NULL);
		}

		return $title;
	}

	public function install()
	{
		$this->db->insert('nf_settings_addons', [
			'name'       => $this->__info['name'],
			'type'       => $this->__info['type'],
			'is_enabled' => TRUE
		]);

		return $this;
	}

	public function uninstall($remove = TRUE)
	{
		$this->db	->where('name', $this->__info['name'])
					->where('type', $this->__info['type'])
					->delete('nf_settings_addons');

		if ($remove)
		{
			dir_remove($this->__info['type'].'s/'.$this->__info['name']);
		}

		return $this;
	}

	public function reset()
	{
		$this->uninstall(FALSE);
		//$this->config->reset();
		$this->install();

		return $this;
	}
}
