<?php
include 'header.php';
check_admin() or die('0');

if(isset($_POST['id_invite'])){
	$success = supprime_invite($_POST['id_invite']);
	
	if($success){
		die('1');
	}
}

die('0');

?>