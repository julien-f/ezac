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
include 'edit_resa.php';

$query="SELECT * FROM hebergement_hotes WHERE id_evenement = '" . mysql_real_escape_string($event_id) . "'";
$result_hotes = mysql_query($query, $db_link);

$element_menu_selectionne = 1;

include 'html_header.php';
?>


				<h2>// Liste des propositions d'hébergement</h2>

				<div id="liste_propositions">

<?php while($hote = mysql_fetch_assoc($result_hotes)) { ?>

<?php
	$list_invites = get_invites($hote['id'], $event_id);
	$tab_invites = array();
	while($list_invites && $invite = mysql_fetch_array($list_invites)){
		$tab_invites[] = $invite;
	}
	$nb_places_prises = count($tab_invites);
	$nb_places_restantes = $hote['nb_places'] - $nb_places_prises;

	if(isset($invite_error) && $invite_error && $input_hote_id == $hote['id']){
		$qui=$input_qui;
		$tel=$input_telephone;
		$email=$input_email;
		$html_class="input_text modified";
		$invite_error=false;
	}else{
		$qui="";
		$tel="";
		$email="";
		$html_class="input_text to_be_filled";
	}
?>
					<div class="proposition" id="proposition<?php echo $hote['id']; ?>">
						<input type="hidden" name="id" value="<?php echo $hote['id']; ?>" />
						<div class="zone_reservation">
							<p class="textes_places">
								<span class="texte_places_restantes">Places restantes : <span class="nb_places_restantes"><?php echo $nb_places_restantes; ?></span></span><br />
								<span class="texte_places_prises">Places prises : <span class="nb_places_prises"><?php echo $nb_places_prises; ?></span></span><br />
								<?php if($nb_places_prises > 0){ ?>
									<p class="resume_invites">
									<?php foreach($tab_invites as $invite){ ?>
										<span class="invite">
											- <?php echo $invite['qui']; ?>
											(<?php echo ($invite['telephone'] && $invite['email']) ? ($invite['telephone'] . ', ' . $invite['email']) : ($invite['telephone'] . $invite['email']); ?>)
										</span>
										<br />
									<?php } ?>
									</p>
								<?php } ?>

								<?php if($nb_places_restantes > 0){ ?>
								<a href="#" class="bouton_afficher_form_demande">Demander une place</a>
								<div class="zone_form_demande">
									<div class="form_demande">
										<h4>Demander une place</h4>
										<form method="post" action="#">
											<input type="hidden" name="action" value="reserve_room" />
											<input type="hidden" name="hote_id" value="<?php echo $hote['id']; ?>" />
											<label for="qui_<?php echo $hote['id']; ?>">Nom</label><input id="qui_<?php echo $hote['id']; ?>" class="<?php echo $html_class; ?>" type="text" name="qui" value="<?php echo $qui; ?>" /><br />
											<label for="telephone_<?php echo $hote['id']; ?>">Téléphone</label><input id="telephone_<?php echo $hote['id']; ?>" class="<?php echo $html_class; ?>" type="text" name="telephone" value="<?php echo $tel; ?>" /><br />
											<label for="email_<?php echo $hote['id']; ?>">E-mail</label><input id="email_<?php echo $hote['id']; ?>" class="<?php echo $html_class; ?>" type="text" name="email" value="<?php echo $email; ?>" /><br />
											<input class="bouton_valider_form_demande" type="submit" value="Valider" />
										</form>
									</div>
								</div>
								<?php } ?>
							</p>
						</div>
						<div class="zone_infos_hebergement">
							<h3>Hébergement de <span class="nom_hote"><?php echo $hote['qui']; ?></span></h3>
							<p class="adresse"><?php echo $hote['adresse']; ?></p>

							<?php if($hote['telephone'] != ''){ ?>
							<span class="telephone">téléphone : <?php echo $hote['telephone']; ?></span>
							<?php } ?>

							<?php if($hote['email'] != ''){ ?>
							<span class="email">e-mail : <?php echo $hote['email']; ?></span>
							<?php } ?>
							<br />
							<div class="zone_remarques">
								<p class="infos_couchage"><?php echo $hote['couchage']; ?></p>
								<p class="infos_remarque"><?php echo $hote['remarque']; ?></p>
							</div>
						</div>
						<div class="clear"></div>

					</div>



<?php } ?>

				</div>
<?php include 'html_footer.php'; ?>
<?php include 'footer.php';
