<?php
session_start();
include_once '../config/config-database.php';

/**
* Recherche une identification en POST ou en session
* Retourne true ou false selon qu'une identification valide est trouvée ou non
*/
function check_admin(){
	$admin_login = 'karine';
	$admin_password = 'KBadmin#85';

	//Recherche d'une identification valide
	if(isset($_POST['login']) && isset($_POST['password'])){
		if($_POST['login'] == $admin_login && $_POST['password'] == $admin_password){
			$_SESSION['admin'] = true;
			return true;
		}
	}
	elseif(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
		return true;
	}
	//Aucune identification trouvée
	$_SESSION['admin'] = false;
	return false;
}


function get_room_left($hote_id){
	global $db_link;
	$query = "SELECT COUNT(*) AS nb_room
			 FROM hebergement_invites
			 WHERE id_hote = '$hote_id'";
	$result = mysql_query($query, $db_link) or die("Select invites query failed" . mysql_error());
	$count = mysql_fetch_array($result);

	$query = "SELECT nb_places
				 FROM hebergement_hotes
				 WHERE id = '$hote_id'";
	//print $query;
	$result = mysql_query($query, $db_link) or die("Select hote query failed" . mysql_error());
	$nb_place = mysql_fetch_array($result);
	return $nb_place["nb_places"]-$count["nb_room"];
}

function insert_invite($qui, $tel, $email, $hote_id)
{
	global $db_link;

	if(get_room_left($hote_id)>0){
		$query="INSERT INTO hebergement_invites VALUES
		(NULL,
		'$qui',
		'$tel',
		'$email',
		NOW(),
		'$hote_id')";
		//print $query;
		mysql_query($query, $db_link) or die("Insert impossible in table hebergement_invites" . mysql_error());
	}else{
		print "<script type=\"text/javascript\">alert(\"Il n'y a plus de place chez cet hote.\");</script>";
	}
}

function insert_hote($id_evenement, $qui, $adresse, $tel, $email, $nb_places, $couchage, $remarque)
{
	global $db_link;

		$query="INSERT INTO hebergement_hotes VALUES
		(NULL,
		'$id_evenement',
		'$qui',
		'$adresse',
		'$tel',
		'$email',
		'$nb_places',
		'$couchage',
		'$remarque',
		NOW())";
		//print $query;
		mysql_query($query, $db_link) or die("Insert impossible in table hebergement_hotes : " . mysql_error());
}

function supprime_invite($id_invite){
	global $db_link;

	$query="DELETE FROM hebergement_invites
			WHERE id = '" . $id_invite . "';";
	$success = mysql_query($query, $db_link);
	if($success){
		return true;
	}
	else return false;
}

function supprime_hote($id_hote){
	global $db_link;

	$query="DELETE FROM hebergement_hotes
			WHERE id = '" . $id_hote . "';";
	$success = mysql_query($query, $db_link);
	if($success){

		$query="DELETE FROM hebergement_invites
				WHERE id_hote = '" . $id_hote . "';";
		$success = mysql_query($query, $db_link);
		if($success){
			return true;
		}
	}

	return false;
}

/**
 * fill an array with "evenement" data
 * @param int $event_id ID of the wanted "evenement"
 * return array
 */
function get_tab_evenement($event_id){
	global $db_link;

	$query_evenement = "SELECT * FROM hebergement_evenement WHERE id = '" . mysql_real_escape_string($event_id) . "';";
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

	$query="SELECT * FROM hebergement_invites WHERE id_hote=$id";
	return mysql_query($query, $db_link);
}

//----------------------------------------------------------------------------------------------

//Récupère l'id d'événement qui nous intéresse
if(isset($_GET['id_evenement'])){
	$event_id = $_GET['id_evenement'];
	$_SESSION['id_evenement'] = $event_id;
}
elseif(isset($_SESSION['id_evenement'])){
	$event_id = $_SESSION['id_evenement'];
}
else{
	//$event_id = null;
	//Evenement par défaut : disons que c'est tradzone
	$event_id = 1;
}

$tab_evenement = get_tab_evenement($event_id);

if(!$tab_evenement){
	$_SESSION['id_evenement'] = null;
	header('location: http://lacampanule.free.fr/');
	die();
}

header('Content-Type: text/html; charset=ISO-8859-1');
