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
final class Ezac_Manager_Pdo implements Ezac_Manager
{
	function __construct(PDO $pdo)
	{
		$pdo->setAttribute(
			PDO::ATTR_ERRMODE,
			PDO::ERRMODE_EXCEPTION
		);

		$this->_pdo = $pdo;
	}

	/**
	 * @param string $table
	 * @param array  $fields
	 */
	function create($table, array $fields)
	{
		$keys = array_map(
			__CLASS__.'::_quote',
			array_keys($fields)
		);
		$fields = array_map(
			array($this->_pdo, 'quote'),
			$fields
		);

		$insert =
			'INSERT INTO '.self::_quote($table)
			.' ('.implode(',', $keys).')'
			.' VALUES ('.implode(',', $fields).')'
			;

		$n = $this->_pdo->exec($insert);

		assert('$n === 1');
	}

	/**
	 * @param string $table
	 * @param array  $criteria
	 */
	function delete($table, array $criteria)
	{
		$delete =
			'DELETE FROM '.self::_quote($table)
			.$this->_where($criteria)
			;

		return $this->_pdo->exec($delete);
	}

	/**
	 * @param string $table
	 * @param string $primary_key (usually “id”).
	 * @param array  $fields
	 */
	function refresh($table, $primary_key, array &$fields)
	{
		assert('isset($fields[$primary_key])');

		$stmt = $this->_pdo->prepare(
			'SELECT * FROM '.self::_quote($table)
			.' WHERE '.self::_quote($primary_key).' = ?'
		);

		$success = $stmt->execute($fields[$primary_key]);
		assert('$success');
		assert('$stmt->rowCount() === 1');

		$fields = $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * @param string     $class
	 * @param string     $table
	 * @param array|null $criteria
	 *
	 * @return Ezac_Bean[]
	 */
	function search($class, $table, array $criteria = null)
	{
		$select =
			'SELECT * FROM '.self::_quote($table)
			.$this->_where($criteria)
			;

		$stmt = $this->_pdo->prepare($select);
		$success = $stmt->execute();
		assert('$success');

		$beans = array();
		while ($fields = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$beans[] = new $class($this, $fields);
		}
		return $beans;
	}

	/**
	 * @param string $table
	 * @param string $primary_key (usually “id”).
	 * @param array  $fields
	 */
	function update($table, $primary_key, array $fields)
	{
		assert('isset($fields[$primary_key])');

		$id = $fields[$primary_key];
		unset($fields[$primary_key]);

		$assignments = array();
		foreach ($fields as $key => $val)
		{
			$assignments[] = $this->_quote($key).' = '.$this->_pdo->quote($val);
		}

		$update =
			'UPDATE '.self::_quote($table)
			.' SET '.implode(', ', $assignments)
			.$this->_where(array($primary_key => $id))
			;

		$n = $this->_pdo->exec($update);

		assert('$n === 1');
	}

	/**
	 * Quotes an identifier.
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private static function _quote($value)
	{
		return '`'.str_replace('`', '``', $value).'`';
	}

	private function _where(array $criteria = null)
	{
		if (empty($criteria))
		{
			return '';
		}

		$wheres = array();
		foreach ($criteria as $key => $val)
		{
			$wheres[] = $this->_quote($key).' = '.$this->_pdo->quote($val);
		}
		return ' WHERE '.implode(' AND ', $wheres);
	}
}
