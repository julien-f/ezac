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

/**
 *
 */
abstract class Ezac_Bean
{
	/**
	 * @return array
	 */
	abstract protected static function _getDefinition();

	final function __construct(Ezac_Manager $manager, array $properties = null)
	{
		$class = get_class($this);

		if (!isset(self::$_defs[$class]))
		{
			self::$_defs[$class] =
				call_user_func(array($class, '_getDefinition'))
				+ array(
					'primary_key' => 'id',
					'properties'  => array(),
				)
				;
		}

		$this->_manager    = $manager;
		$this->_properties = (array) $properties;
	}

	final function __get($name)
	{
		return $this->_properties[$name];
	}

	final function __set($name, $value)
	{
		$this->_properties[$name] = $value;
	}

	final function save()
	{
		$defs = self::$_defs[get_class($this)];

		$this->_manager->save(
			$defs['table'],
			$defs['primary_key'],
			$this->_properties
		);
	}

	final function refresh()
	{
		$defs = self::$_defs[get_class($this)];

		$this->_manager->refresh(
			$defs['table'],
			$defs['primary_key'],
			$this->_properties
		);
	}

	/**
	 * @var array
	 */
	static private $_defs;

	/**
	 * @var Ezac_Manager
	 */
	private $_manager;

	/**
	 * @var array
	 */
	private $_properties;
}
