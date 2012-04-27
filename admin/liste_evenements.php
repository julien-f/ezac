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
check_admin() or header('location:login.php');
include 'edit_resa.php';

$query="SELECT * FROM hebergement_evenement ORDER BY id DESC;";
$result_evenements = mysql_query($query, $db_link);

$element_menu_selectionne = 1;

include 'html_header.php';
?>


				<h2>// Liste des événements</h2>

				<div id="liste_propositions">

<?php while($un_evenement = mysql_fetch_assoc($result_evenements)) { ?>
					<div class="evenement" style="border: 1px solid #<?php echo $un_evenement['code_couleur']; ?>; box-shadow: 0 0 4px #<?php echo $un_evenement['code_couleur']; ?>;">
						<a href="reservation.php?id_evenement=<?php echo $un_evenement['id']; ?>">
							<img src="../img/bannieres/<?php echo $un_evenement['banniere']; ?>" alt="<?php echo $un_evenement['nom']; ?>" />
						</a>
					</div>
<?php } ?>

				</div>
<?php include '../html_footer.php'; ?>
<?php include 'footer.php';
