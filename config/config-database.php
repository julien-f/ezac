<?php
/*
 * This file is part of Ezac.
 *
 * Ezac is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ezac is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ezac.  If not, see <http://www.gnu.org/licenses/>.
 */

$user="";
$password="";
$database="";
$sql_server="";

$db_link = mysql_connect($sql_server, $user, $password) or die("Impossible de se connecter a la base");
mysql_select_db($database, $db_link) or die( "Unable to select database");

mysql_set_charset('utf8', $db_link);
