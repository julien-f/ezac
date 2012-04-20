<?php
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
