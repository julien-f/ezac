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

$this->extend(__DIR__.'/base.html.php', 'content');
?>

<table>
  <thead>
	<tr>
	  <th>Nom</th>
	  <th>Rang</th>
	  <th>Date d'inscription</th>
	  <th>Courriel</th>
	  <th>Actif ?</th>
	</tr>
  </thead>
  <tbody>
	<?php foreach ($users as $user): ?>
	<tr>
	  <td><?php echo $user->name; ?></td>
	  <td><?php echo $user->rank; ?></td>
	  <td><?php echo $user->ctime; ?></td>
	  <td><?php echo $user->email; ?></td>
	  <td><?php echo $user->active; ?></td>
	  <td><a href="?a=admin&u=<?php echo $user->id; ?>">x</a></td>
	</tr>
	<?php endforeach; ?>
  </tbody>
</table>
