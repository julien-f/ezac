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
	final static function create(Ezac_Manager $manager, array $fields)
	{
		$manager->create(static::$_table, $fields);
	}

	final static function delete(Ezac_Manager $manager, $id)
	{
		$manager->delete(static::$_table, array(static::$_primary_key => $id));
	}

	/**
	 * @param Ezac_Manager $manager
	 * @param mixed        $id
	 *
	 * @return Ezac_Bean
	 */
	final static function get(Ezac_Manager $manager, $id)
	{
		$beans = static::search($manager, array(static::$_primary_key => $id));

		assert('!empty($beans)');

		return reset($beans);
	}

	/**
	 * @param Ezac_Manager $manager
	 * @param array|null   $criteria
	 *
	 * @return Ezac_Bean[]
	 */
	final static function search(Ezac_Manager $manager, array $criteria = null)
	{
		return $manager->search(get_called_class(), static::$_table, $criteria);
	}

	/**
	 *
	 */
	final function __construct(Ezac_Manager $manager, array $fields = null)
	{
		$this->_manager = $manager;
		$this->_fields  = isset($fields) ? $fields : array();
	}

	/**
	 *
	 */
	final function __get($name)
	{
		if (isset($this->_fields[$name]))
		{
			return $this->_fields[$name];
		}

		return $this->{'_get'.$name}();
	}

	/**
	 *
	 */
	final function __set($name, $value)
	{
		$this->_fields[$name] = $value;
	}

	/**
	 *
	 */
	final function save()
	{
		$this->_manager->update(
			static::$_table,
			static::$_primary_key,
			$this->_fields
		);
	}

	/**
	 *
	 */
	final function refresh()
	{
		$this->_manager->refresh(
			static::$_table,
			static::$_primary_key,
			$this->_fields
		);
	}

	/**
	 * @var string
	 */
	protected static $_primary_key = 'id';

	/**
	 * @var Ezac_Manager
	 */
	protected $_manager;

	/**
	 * @var array
	 */
	private $_fields;
}
