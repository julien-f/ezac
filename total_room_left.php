<?php
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
