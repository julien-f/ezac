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

$(document).ready(function(){

	//Lien pour supprimer un invité

	$('.lien_suppression_invite').click(function(e){
		e.preventDefault();

		if(confirm("Supprimer définitivement cette réservation ?")){

			var id_invite_selectionne = $(this).parents('.invite').find('input[name=id_invite]').val();
			$.post("ajax_supprime_invite.php", { id_invite: id_invite_selectionne },
				function(data){
				if(data == '1'){
					invite_supprime = $('#invite_' + id_invite_selectionne);
					zone_affichage_infos = $(invite_supprime).parents('.zone_reservation');

					$(invite_supprime).remove();

					var nb_places_restantes = parseInt($(zone_affichage_infos).find('.nb_places_restantes').html()) + 1;
					var nb_places_prises = parseInt($(zone_affichage_infos).find('.nb_places_prises').html()) - 1;

					$(zone_affichage_infos).find('.nb_places_restantes').html(nb_places_restantes)
					$(zone_affichage_infos).find('.nb_places_prises').html(nb_places_prises)
				}
			});

		}
	});

	//Lien pour supprimer un hébergement

	$('.lien_suppression_hote').click(function(e){
		e.preventDefault();

		if(confirm("Supprimer définitivement cet hébergement, et toutes les réservations qui y sont rattachées ?")){

			var id_hote_selectionne = $(this).parents('.proposition').find('input[name=id]').val();
			$.post("ajax_supprime_hote.php", { id_hote: id_hote_selectionne },
				function(data){
				if(data == '1'){
					hote_supprime = $('#proposition' + id_hote_selectionne);
					$(hote_supprime).remove();
				}
			});

		}
	});

});
