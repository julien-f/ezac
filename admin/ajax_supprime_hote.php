<?php
include 'header.php';
check_admin() or die('0');

if(isset($_POST['id_hote'])){
	$success = supprime_hote($_POST['id_hote']);

	if($success){
		die('1');
	}
}

die('0');
