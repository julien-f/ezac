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
<h2>Connexion</h2>

<form method="post" action="?login">
  <p>
	<label>
	  Identifiant :
	  <input type="text" name="name" placeholder="identifiant" autofocus />
	</label>
	<label>
	  Mot de passe :
	  <input type="password" name="password" placeholder="mot de passe" />
	</label>
  </p>
  <p>
	<input type="submit" value="Valider" />
  </p>
</form>

<p>Connexion requise pour accéder à votre espace privé.</p>
