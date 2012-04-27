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

include 'header.php';
if(check_admin()){
	header('location:liste_evenements.php');
}
elseif(isset($_POST['login'])){
	$message_erreur = "Ces identifiants sont incorrects.";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<title>
			Connexion à l'administration
		</title>
		<link rel="stylesheet" type="text/css" href="admin.css" />
	</head>
	<body id="page_connexion">
		<?php if(isset($message_erreur)){ ?>
		<div id="zone_messages">
			<span class="message_erreur"><?php echo $message_erreur; ?></span>
		</div>
		<?php } ?>
		<h1 id="titre_page_connexion">Entrez vos identifiants pour vous connecter au mode Admin</h1>
		<!--Panneau de connexion-->
		<div id="panneau_login">
			<form action="#" method="post">
				<fieldset>
					<input type="hidden" name="action" value="connexion" />
					<label>
						Identifiant :
						<input type="text" name="login" />
					</label>
					<label>
						Mot de passe :
						<input type="password" name="password" />
					</label>
					<input type="submit" id="bouton_connexion" value="Valider" />
				</fieldset>
			</form>
		</div>
	</body>
</html>
