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

final class _Ezac_Template_Helper
{
	function __construct($file, $var, $vars)
	{
		$this->_file = $file;
		$this->_var  = $var;
		$this->_vars = $vars;

		ob_start();
	}

	function __destruct()
	{
		$data = ob_get_clean();

		$t = new Ezac_Template(
			$this->_file,
			array($this->_var => $data) + $this->_vars
		);
		$t->render();
	}

	private
		$_file,
		$_var,
		$_vars;
}

final class Ezac_Template
{
	function __construct($file, array $vars = null)
	{
		$this->_file = $file;
		$this->_vars = isset($vars) ? $vars : array();
	}

	function extend($file, $var)
	{
		$this->_plop = null;
		$this->_plop = new _Ezac_Template_Helper($file, $var, $this->_vars);
	}

	function render()
	{
		extract($this->_vars);
		require($this->_file);
		$this->_plop = null;
	}

	private
		$_file,
		$_plop,
		$_vars;
}