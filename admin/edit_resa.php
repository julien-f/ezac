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

check_admin() or header('location:login.php');

foreach ($_POST as $name => $value){
	${'input_'.$name} = $value;
}
if(isset($input_action)){
	switch($input_action){
		case "reserve_room":
			if($input_qui == ""){
				print "<script type=\"text/javascript\">alert(\"Vous devez donner un nom (pseudos autorisés).\");</script>";
				$hote_error=true;
			}
			elseif($input_email == "" and $input_telephone == ""){
				print "<script type=\"text/javascript\">alert(\"Vous devez renseigner au moins un moyen de contact.\");</script>";
				$invite_error=true;
			}else{
				insert_invite($input_qui, $input_telephone, $input_email, $input_hote_id);
			}
		break;

		case "supprime_invite":
			if($input_id_invite){
				supprime_invite($input_id_invite);
			}
		break;

	}
}
