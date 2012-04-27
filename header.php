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

session_start();
include_once 'config/config-database.php';
include_once 'config/mymail.php';

function hide_email($email) { $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz'; $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999); for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])]; $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";'; $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));'; $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"'; $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>'; return '<span id="'.$id.'">[Adresse email protégée par Javascript. Activez Javascript pour l\'afficher]</span>'.$script; }

function get_room_left($hote_id){
	global $db_link;
	$hote_id=intval($hote_id);

	$query = "SELECT COUNT(*) AS nb_room
			 FROM hebergement_invites
			 WHERE id_hote = '$hote_id'";
	$result = mysql_query($query, $db_link) or die("Select invites query failed" . mysql_error());
	$count = mysql_fetch_array($result);

	$query = "SELECT nb_places
				 FROM hebergement_hotes
				 WHERE id = $hote_id";
	//print $query;
	$result = mysql_query($query, $db_link) or die("Select hote query failed" . mysql_error());
	$nb_place = mysql_fetch_array($result);
	return $nb_place["nb_places"]-$count["nb_room"];
}

function get_hote($hote_id){
	global $db_link;
	$hote_id=intval($hote_id);
	$query = "SELECT `qui` , `adresse` , `telephone` , `email` , `couchage` , `remarque`
		FROM `hebergement_hotes`
		WHERE `id` =$hote_id";
	$result = mysql_query($query, $db_link) or die("get hot name query failed" . mysql_error());
	$row = mysql_fetch_array($result);
	return $row;
}

function insert_invite($qui, $tel, $email, $origine, $hote_id)
{
	global $db_link;

	if(get_room_left($hote_id)>0){
		$query="INSERT INTO hebergement_invites VALUES
		(NULL,'".
		mysql_real_escape_string($qui)."','".
		mysql_real_escape_string($tel)."','".
		mysql_real_escape_string($email)."','".
		mysql_real_escape_string($origine)."',".
		"NOW(),".
		intval($hote_id).")";

		//print $query;
		mysql_query($query, $db_link) or die("Insert impossible in table hebergement_invites" . mysql_error());

		//envoi email à l'hébergeur
		$hote=get_hote($hote_id);

		$string_hote="
		Bonjour ".$hote['qui']. ",<br /><br />

		Nous vous informons que <strong>$qui</strong> a réservé un couchage suite à la proposition d'hébergement que vous aviez effectuée sur <a href=\"http://hebergement.folkafon.com\" >http://hebergement.folkafon.com</a>
		<br />Voici ses coordonnées en cas de besoin.
		<ul>
		<li>Nom : <strong>$qui</strong> ($origine)</li>
		<li>Tel : $tel</li>
		<li>Email : $email</li></ul>

		Merci beaucoup pour votre accueil !
		<br /><br />
		NB : En cas de problème avec ce service, nous vous invitons à répondre à ce mail en précisant le souci rencontré.
		<br /><br />
		---<br />
		L'association Folkafon<br />
		<a href=\"http://www.folkafon.com\">
		<img src='http://letonio.fr/tZ/pic/nuittrad_2012.png' alt=\"14 avril 2012 : Nuit Trad'actuelle 2012 !\" /></a>
		";

		if (smtpmailer($hote['email'], 'hebergement@folkafon.com', 'Hebergement - Folkafon', '[Nuit Trad\'actuelle | 1 Couchage réservé !]', $string_hote)) {
			// do something
		}
		//if (!empty($error)) echo $error;



		//envoi de mail à l'hébergé
		$string_invited=
		"Bonjour $qui, <br /><br />

		Vous avez réservé une place de couchage chez ". $hote['qui'].".<br />
		<strong>N'oubliez pas de vous mettre d'accord avec votre hôte par téléphone ou par mail.</strong><br />
		Voici ses coordonnées :<br />
		<ul>
			<li><strong>".$hote['qui']."</strong></li>
			<li>Lieu : ".$hote['adresse']."</li>
			<li>Téléphone : ".$hote['telephone']."</li>
			<li>Email : <a href=\"mailto:".$hote['email']."\" >".$hote['email']."</a></li>

		</ul>
		Précisions éventuelles : ".nl2br(stripslashes($hote['couchage']))."<br />".nl2br(stripslashes($hote['remarque']))."
		<br />		<br />

		Merci d'avoir utilisé le <a href=\"http://hebergement.folkafon.com\" >service d'hébergement</a> de l'association Folkafon.<br /><br />

		NB : En cas de problème avec ce service, nous vous invitons à répondre à ce mail en précisant le souci rencontré. (hebergement@folkafon.com)

		---<br />
		L'association Folkafon<br />
		<a href=\"http://www.folkafon.com\">
		<img src='http://letonio.fr/tZ/pic/nuittrad_2012.png' alt=\"14 avril 2012 : Nuit Trad'actuelle 2012 !\" /></a>
		";
			//echo $string;


		if (smtpmailer($email, 'hebergement@folkafon.com', 'Hebergement - Folkafon', '[Nuit Trad\'actuelle | Votre hébergement]', $string_invited)) {
			// do something
		}
		//if (!empty($error)) echo $error;
		//if (!empty($error)) echo $error;



	}else{
		print "<script type=\"text/javascript\">alert(\"Il n'y a plus de place chez cet hote.\");</script>";
	}
}

function insert_hote($id_evenement, $qui, $localisation, $tel, $email, $nb_places, $couchage, $remarque)
{
	global $db_link;

		$query="INSERT INTO hebergement_hotes VALUES
		(NULL,'".
		mysql_real_escape_string($id_evenement)."','".
		mysql_real_escape_string($qui)."','".
		mysql_real_escape_string($localisation)."','".
		mysql_real_escape_string($tel)."','".
		mysql_real_escape_string($email)."','".
		mysql_real_escape_string($nb_places)."','".
		mysql_real_escape_string($couchage)."','".
		mysql_real_escape_string($remarque)."',
		NOW())";
		//print $query;
		mysql_query($query, $db_link) or die("Insert impossible in table hebergement_hotes" . mysql_error());

		//envoi de mail
		$string=
		"Bonjour $qui, <br /><br />

		Vous avez ajouté $nb_places place(s) de couchage pour accueillir des danseurs ou des musiciens lors de la Nuit Trad'actuelle..<br />
		<strong>Nous vous en remercions vivement ! </strong><br />Afin de garnir l'offre existante, nous vous invitons à transférer le lien <a href=\"http://hebergement.folkafon.com\">http://hebergement.folkafon.com</a> à vos connaissances susceptibles de faire preuve d'autant d'hospitalité que vous !

		<br /><br />
		NB : En cas de problème avec ce service, nous vous invitons à répondre à ce mail en précisant le souci rencontré. (hebergement@folkafon.com)
		<br /><br />
		Merci d'avoir utilisé le service d'hébergement de l'association Folkafon et à très bientôt !<br /><br />

		---<br />
		L'association Folkafon<br />
		<a href=\"http://www.folkafon.com\">
		<img src='http://letonio.fr/tZ/pic/nuittrad_2012.png' alt=\"14 avril 2012 : Nuit Trad'actuelle 2012 !\" /></a>
		";


		if (smtpmailer($email, 'hebergement@folkafon.com', 'Hebergement - Folkafon', '[Nuit Trad\'actuelle | Hébergement]', $string)) {
			// do something
		}
		if (!empty($error)) echo $error;
}
/**
 * fill an array with "evenement" data
 * @param int $event_id ID of the wanted "evenement"
 * return array
 */
function get_tab_evenement($event_id){
	global $db_link;

	$query_evenement = "SELECT * FROM hebergement_evenement WHERE id = " . intval($event_id) ;

	$res_evenement = mysql_query($query_evenement, $db_link);
	if(!$res_evenement) return null;
	$tab_evenement = mysql_fetch_array($res_evenement);

	return $tab_evenement;
}

/**
 * Select all invites from a host
 * @param int $id
 * return resource list of invites
 */
function get_invites($id, $event_id)
{
	global $db_link;

	$query="SELECT * FROM hebergement_invites WHERE id_hote= " . intval($id);
	return mysql_query($query, $db_link);
}

//----------------------------------------------------------------------------------------------

//Récupère l'id d'événement qui nous intéresse
if(isset($_GET['id_evenement'])){
	$event_id = intval($_GET['id_evenement']);
	$_SESSION['id_evenement'] = $event_id;
}
elseif(isset($_SESSION['id_evenement'])){
	$event_id = intval($_SESSION['id_evenement']);
}
else{
	//$event_id = null;
	//Evenement par défaut : disons que c'est funam'bals
	$event_id = 2;
}

$tab_evenement = get_tab_evenement($event_id);

if(!$tab_evenement){
	$_SESSION['id_evenement'] = null;
	header('location: http://lacampanule.free.fr/');
	die();
}
