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
interface Ezac_Manager
{
	/**
	 * @param string $table
	 * @param string $primary_key (usually “id”).
	 * @param array  $properties
	 */
	function save($table, $primary_key, array $properties);

	/**
	 * @param string $table
	 * @param string $primary_key (usually “id”).
	 * @param array  $properties
	 */
	function refresh($table, $primary_key, array &$properties);

	/**
	 * @param string     $table
	 * @param array|null $criterion
	 *
	 * @return Ezac_Bean[]
	 */
	function search($table, array $criterion = null);
}
