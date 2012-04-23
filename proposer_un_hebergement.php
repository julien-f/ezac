<?php
include 'header.php';
include 'edit_resa.php';



if(isset($hote_error) && $hote_error){
	$qui=$input_qui;
	$localisation=$input_localisation;
	$tel=$input_telephone;
	$email=$input_email;
	$nb_places=$input_nb_places;
	$couchage=$input_couchage;
	$commentaires=$input_commentaires;
	$html_class="input_text modified";
}else{
	$qui="";
	$localisation="";
	$tel="";
	$email="";
	$nb_places="1";
	$couchage="";
	$commentaires="";
	$html_class="input_text to_be_filled";
}



$element_menu_selectionne = 2;

include 'html_header.php';
?>

<h2>//Proposer un hébergement</h2>

<div id="zone_formulaire_proposition">

	<h3>Indiquez ci-dessous les informations concernant votre proposition.</h3>
	
	<form method="post" action="reservation.php?action=add_room">
		<input type="hidden" name="action" value="add_room" />
		<label for="proposition_input_qui">Votre nom* : </label><input id="proposition_input_qui" class="<?php echo $html_class; ?>" type="text" placeholder="Votre nom (ou pseudo)" name="qui" value="<?php echo $qui; ?>" /><br />
		<label for="proposition_input_nb_places">Le nombre de places que vous proposez* : </label><input id="proposition_input_nb_places" class="<?php echo $html_class; ?>" type="text" name="nb_places" value="<?php echo $nb_places; ?>" /><br />
		<label for="proposition_localisation">Localisation* : </label><input id="proposition_localisation" class="<?php echo $html_class; ?>" type="text" name="localisation" placeholder="Quartier/ville de l'hébergement" value="<?php echo $localisation; ?>" /><br />
		<p class="titre_fieldset">Il est conseillé de remplir votre email ET votre téléphone.</p>
		<label for="proposition_email">E-mail* : </label><input id="proposition_email" class="<?php echo $html_class; ?>" placeholder="biniou44@exemple.com" type="text" name="email" value="<?php echo $email; ?>" /><br />
		<label for="proposition_telephone">Téléphone : </label><input id="proposition_telephone" class="<?php echo $html_class; ?>" type="text" placeholder="06 xx xx xx xx" name="telephone" value="<?php echo $tel; ?>" /><br />
		
		
		<br />
		
		<div class="zone_textarea">
			<label for="proposition_couchage">Infos couchage : <span class="supplement_label">(ex: amener matelas, sac de couchage...)</span></label>
			<textarea id="proposition_couchage" cols="30" rows="5" name="couchage"><?php echo $couchage; ?></textarea>
		</div>
		
		<div class="zone_textarea">
			<label for="proposition_remarques">Remarques : <span class="supplement_label">(ex: voiture nécessaire...)</span></label>
			<textarea id="proposition_remarques" cols="30" rows="5" name="commentaires" placeholder="Voiture nécessaire ? Tournée de croissants à prévoir le dimanche matin ?"><?php echo $commentaires; ?></textarea>
		</div>
		
		<div class="clear"> *donnée obligatoire.</div>
		
		
		<input class="bouton_valider_form_proposition" type="submit" value="Valider" />
	
	</form>

</div>


<?php include 'html_footer.php'; ?>
<?php include 'footer.php'; ?>
