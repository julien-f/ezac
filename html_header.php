<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
	<head>
		<title>Réservation hebergement - <?php echo $tab_evenement['nom']; ?></title>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link rel="icon" type="image/png" href="http://www.folkafon.com/favicon.ico" />
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>

		<script type="text/javascript" src="js/propositions.js"></script>
		<script type="text/javascript" src="js/proposer.js"></script>
		<style type="text/css">


.element_menu.selectionne, .proposition, #zone_formulaire_proposition{
	border-color: #<?php echo $tab_evenement['code_couleur'];?>;
}

#separateur_zones, .proposition .adresse, .bouton_afficher_form_demande{
	background-color: #<?php echo $tab_evenement['code_couleur'];?>;
}

.proposition h3 .nom_hote, h2, .element_menu.selectionne, .proposition .telephone, .proposition .email{
	color: #<?php echo $tab_evenement['code_couleur'];?>;
}

.bouton_valider_form_demande:hover, .bouton_valider_form_proposition:hover{
	border: 1px solid #<?php echo $tab_evenement['code_couleur'];?>;
	box-shadow: 0px 0px 2px #<?php echo $tab_evenement['code_couleur'];?>;
}

#zone_banniere{
	border: 1px solid #<?php echo $tab_evenement['code_couleur'];?>;
	box-shadow: 0px 0px 4px #<?php echo $tab_evenement['code_couleur'];?>;
}
		</style>
	</head>

	<body>


		<div id="entete">
			<a href="http://www.folkafon.com/">
				<img id="logo_folkafon" alt="Folkafon" src="img/logo_folkafon.png" />
			</a>

			<p id="texte_entete">
				Liste non-exhaustive des hébergements (hôtels, campings...) sur <a href="http://www.folkafon.com/nuit-tradactuelle-2012/infos-pratiques#hebergement" > la page dédiée du site</a>
			</p>
		</div>


		<div id="entete_evenement">
			<p id="texte_entete_evenement">
				Trouvez votre hébergement<br />
				chez l’habitant pour

			</p>
			<div id="zone_banniere">
				<a href="<?php echo $tab_evenement['site_officiel']; ?>">
					<img src="img/bannieres/<?php echo $tab_evenement['banniere']; ?>" alt="<?php echo $tab_evenement['nom']; ?>" />
				</a>
			</div>
		</div>

		<div id="corps">
			<div id="menu">
				<a class="element_menu<?php if($element_menu_selectionne == 1){echo ' selectionne';} ?>" href="reservation.php">Liste des hébergements</a>
				<a class="element_menu<?php if($element_menu_selectionne == 2){echo ' selectionne';} ?>" href="proposer_un_hebergement.php">Proposer un hébergement</a>

				<a class="element_menu<?php if($element_menu_selectionne == 3){echo ' selectionne';} ?>" href="comment_ca_marche.php">Comment ça marche ?</a>

			</div>

			<div id="zone_contenu">

				<div id="separateur_zones">
				</div>
