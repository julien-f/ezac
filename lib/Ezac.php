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

spl_autoload_register(array(
	new Gallic_ClassLoader_PrefixFilter(
		new Gallic_ClassLoader_Standard(array(
			dirname(__FILE__),
		)),
		array('Ezac_')
	),
	'load'
));

////////////////////////////////////////////////////////////////////////////////

final class Ezac
{
	function __construct(Ezac_Manager $manager)
	{
		$this->_manager = $manager;
		$this->_templateDir = __DIR__.'/../templates';
	}

	function run()
	{
		$action = isset($_GET['a']) ? $_GET['a'] : null;

		switch ($action)
		{
		case 'admin':
			$this->$action();
			break;
		default:
			$this->error404();
		}
	}

	function admin()
	{
		if (isset($_GET['u']))
		{
			Ezac_Bean_User::delete($this->_manager, $_GET['u']);
		}

		$this->_renderTemplate(
			'list-users',
			array(
				'users' => Ezac_Bean_User::search($this->_manager),
			)
		);
	}

	function error404()
	{
		$url =
			'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'
			.$_SERVER['HTTP_HOST']
			.$_SERVER['REQUEST_URI']
			;

		$this->_renderTemplate(
			'404',
			array(
				'url' => $url
			)
		);
	}

	private function _renderTemplate($file, array $vars = null)
	{
		isset($vars)
			or $vars = array();

		$vars += array(

			'title' => 'Untitled page',

			'menu' => array(
				'Administration' => '?a=admin',
				'test' => '?login',
				'test1' => '?login',
				'test2' => '?login',
				'test3' => '?login',
				'test4' => '?login',
			),
		);

		$t = new Ezac_Template(
			$this->_templateDir.'/'.$file.'.html.php',
			$vars
		);

		$t->render();
	}

	/**
	 * @var Ezac_Manager
	 */
	private $_manager;

	/**
	 * @var string
	 */
	private $_templateDir;
}
