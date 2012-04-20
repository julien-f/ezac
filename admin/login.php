<?php
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
