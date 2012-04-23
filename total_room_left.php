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

include_once 'config/config-database.php';

//fichier facultatif affichant le nombre de places restantes
// (fait la somme des places offertes moins la somme des places occupées)

$query_total_hotes="SELECT SUM(nb_places) AS nb_hotes FROM hebergement_hotes";
$query_total_invites="SELECT COUNT(*) AS nb_invites FROM hebergement_invites";
$result = mysql_query($query_total_hotes, $db_link) or die("get hot name query failed" . mysql_error());
$count_hotes = mysql_fetch_array($result);

$result = mysql_query($query_total_invites, $db_link) or die("get hot name query failed" . mysql_error());
$count_invites = mysql_fetch_array($result);



echo $count_hotes['nb_hotes']-$count_invites['nb_invites'];
