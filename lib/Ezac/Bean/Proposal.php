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
final class Ezac_Bean_Proposal extends Ezac_Bean
{
	protected static $_table = 'proposals';

	protected static $_fields = array(
		'active',
		'ctime',
		'event_id',
		'user_id',
		'type_id',
		'slots',
	);

	protected function _getEvent()
	{
		return Ezac_Bean_Event::get($this->_manager, $this->event_id);
	}

	protected function _getUser()
	{
		return Ezac_Bean_User::get($this->_manager, $this->user_id);
	}

	protected function _getType()
	{
		return Ezac_Bean_Type::get($this->_manager, $this->type_id);
	}
}
