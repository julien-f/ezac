<?php
/**
 * This file is part of Ezac.
 *
 * Ezac is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ezac is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ezac. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Julien Fontanet <julien.fontanet@isonoe.net>
 * @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
 *
 * @package Ezac
 */

if (!class_exists('Gallic'))
{
	require('Gallic.php');
}

spl_register_autoload(array(
	new Gallic_ClassLoader_PrefixFilter(
		new Gallic_ClassLoader_Standard(array(
			dirname(__FILE__),
		)),
		array('Ezac_')
	),
	'load'
));

////////////////////////////////////////////////////////////////////////////////

/**
 * This base class provides advanced property management.
 */
abstract class Ezac_Base
{
	protected function __construct()
	{
		$class = get_class($this);
		if (!isset(self::$_props_defs[$class]))
		{
			$props = array();

			foreach (
				call_user_func(array($class, '_getProperties'), $class)
				as
				$name => $prop
			)
			{
				$need_attr = false;

				if (isset($prop['getter']))
				{
					FvAssert::_(
						is_callable(array($this, $prop['getter'])),
						'invalid getter for: '.$name
					);

					$prop['get'] = true; // Implied
				}
				elseif (isset($prop['get']))
				{
					$need_attr = true;
				}

				if (isset($prop['setter']))
				{
					FvAssert::_(
						is_callable(array($this, $prop['setter'])),
						'invalid setter for: '.$name
					);

					$prop['set'] = true; // Implied
				}
				elseif (isset($prop['set']))
				{
					$need_attr = true;
				}

				FvAssert::_(
					isset($prop['get']) || isset($prop['set']),
					'property neither readable nor writable: '.$name
				);

				if ($need_attr)
				{
					if (!isset($prop['attr']))
					{
						$prop['attr'] = '_'.$name;
					}

					FvAssert::_(
						property_exists($class, $prop['attr']),
						'invalid attribute “'.$prop['attr'].'” for: '.$name
					);
				}

				$props[$name] = $prop;
			}

			self::$_props_defs[$class] = $props;
		}
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		$class = get_class($this);

		FvAssert::_(
			isset(self::$_props_defs[$class][$name]['get']),
			'no such readable property: '.$name
		);

		$prop = self::$_props_defs[$class][$name];

		if (isset($prop['getter']))
		{
			return $this->{$prop['getter']}($name);
		}

		return $this->{$prop['attr']};
	}

	/**
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function __isset($name)
	{
		$class = get_class($this);

		FvAssert::_(
			isset(self::$_props_defs[$class][$name]['get']),
			'no such readable property: '.$name
		);

		$prop = self::$_props_defs[$class][$name];

		return (isset($prop['getter'])
		        || isset($this->{$prop['attr']}));
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function __set($name, $value)
	{
		$class = get_class($this);

		FvAssert::_(
			isset(self::$_props_defs[$class][$name]['set']),
			'no such writable property: '.$name
		);

		$prop = self::$_props_defs[$class][$name];

		if (isset($prop['setter']))
		{
			return $this->{$prop['setter']}($value, $name); // != parameter order than __set()!
		}
		else
		{
			$this->{$prop['attr']} = $value;
		}
	}

	/**
	 * @todo
	 */
	public function __unset($name)
	{
		FvAssert::notReached('not implemented');
	}

	/**
	 * Note: Do not forget to call the parent class method to declare its
	 *     properties!
	 *
	 * @param string $class The name of the class from wich this method was
	 *     called (≈ late static binding).
	 *
	 * @return array
	 */
	protected static function _getProperties($class)
	{
		return array();
	}

	/**
	 * This array contains an entry per instanciated subclass.
	 *
	 * Each one of these arrays contains a list of properties.
	 *
	 * @param array
	 */
	private static $_props_defs = array();
}

////////////////////////////////////////////////////////////////////////////////

final class Ezac extends Ezac_Base
{
	function __construct()
	{
		parent::__construct();
	}
}
