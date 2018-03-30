<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Array_ extends Library implements \Iterator
{
	protected $_array = [];
	protected $_extends;

	public function __invoke($array = [])
	{
		if (func_num_args() > 1)
		{
			$array = func_get_args();
		}

		$this->_array = (array)$array;

		return $this;
	}

	public function __call($name, $args)
	{
		if ($name == 'empty')
		{
			return empty($this->_array);
		}

		return parent::__call($name, $args);
	}

	public function __get($name)
	{
		if (is_array($value = $this->get($name)))
		{
			$value = NeoFrag()->array($value);
		}

		return $value;
	}

	public function __isset($name)
	{
		return $this->get($name) !== NULL;
	}

	public function get()
	{
		$value = NULL;

		$this->_browse(func_get_args(), -1, function(&$node, $name) use (&$value){
			if (array_key_exists($name, $node))
			{
				$value = $node[$name];
			}
		});

		return $value;
	}

	public function set()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			$node = $value;
		});

		return $this->_extends ?: $this;
	}

	public function merge()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			if ($node !== NULL)
			{
				$node = array_merge($node, array_filter($value, function($a){
					return $a !== '';
				}));
			}
			else
			{
				$node = $value;
			}
		});

		return $this->_extends ?: $this;
	}

	public function append()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			array_push($node, $value);
		});

		return $this->_extends ?: $this;
	}

	public function prepend()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node) use ($value){
			array_unshift($node, $value);
		});

		return $this->_extends ?: $this;
	}

	public function destroy()
	{
		$this->_browse(func_get_args(), -1, function(&$node, $name){
			unset($node[$name]);
		});

		return $this->_extends ?: $this;
	}

	public function move($id, $move_to)
	{
		$child = $this->_array[$id];
		unset($this->_array[$id]);
		$this->_array = array_slice($this->_array, 0, $move_to, TRUE) + [$id => $child] + array_slice($this->_array, $move_to, NULL, TRUE);
		return $this->_extends ?: $this;
	}

	public function sort($callback)
	{
		uasort($this->_array, $callback);
		return $this->_extends ?: $this;
	}

	public function each($callback)
	{
		array_walk($this->_array, function(&$a) use (&$callback){
			$a = is_string($callback) && method_exists($a, $callback) ? $a->$callback() : $callback($a);
		});

		return $this->_extends ?: $this;
	}

<<<<<<< HEAD
	public function filter($callback)
=======
	public function filter($callback = NULL)
>>>>>>> upstream/dev
	{
		if (is_string($callback))
		{
			$this->_array = array_filter($this->_array, function(&$a) use (&$callback){
				return method_exists($a, $callback) ? $a->$callback() : $callback($a);
			});
		}
<<<<<<< HEAD
		else
		{
			$this->_array = array_filter($this->_array, $callback);
		}
=======
		else if ($callback)
		{
			$this->_array = array_filter($this->_array, $callback);
		}
		else
		{
			$this->_array = array_filter($this->_array);
		}
>>>>>>> upstream/dev

		return $this->_extends ?: $this;
	}

	public function traversal($callback)
	{
		return $this->each(function(&$a) use (&$callback){
			if (is_a($a, 'NF\\NeoFrag\\Libraries\\Array_'))
			{
				$a->traversal($callback);
			}
			else
			{
				$callback($a);
			}
		});
	}

	public function __toString()
	{
		return implode($this->_array);
	}

	public function __toArray()
	{
		return $this->_array;
	}

	public function __sleep()
	{
		return ['_array'];
	}

<<<<<<< HEAD
	public function __extends($extends)
	{
		$this->_extends = $extends;
		return $this;
=======
	public function __extends($extends = NULL)
	{
		if ($extends)
		{
			$this->_extends = $extends;
			return $this;
		}
		else
		{
			return $this->_extends ?: $this;
		}
>>>>>>> upstream/dev
	}

	protected function _browse()
	{
		$args     = func_get_args();
		$callback = array_pop($args);
		$index    = array_shift($args);

		$node = &$this->_array;

		$n = NULL;

		if ($limit = array_shift($args))
		{
			$n = count($index) + $limit;
		}

		$i = 0;

		while (($name = current($index)) && ($n === NULL || $i++ < $n))
		{
			if (!array_key_exists($name, $node))
			{
				$node[$name] = [];
			}

			$node = &$node[$name];

			next($index);
		}

		$callback($node, current($index));
	}

	public function current()
	{
		return current($this->_array);
	}

	public function key()
	{
		return key($this->_array);
	}

	public function next()
	{
		next($this->_array);
	}

	public function rewind()
	{
		reset($this->_array);
	}

	public function valid()
	{
		return key($this->_array) !== NULL;
	}
}
