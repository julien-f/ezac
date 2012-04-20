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

	//Soumission du formulaire de demande
	$('.bouton_valider_form_proposition').click(function(a){
		a.preventDefault();
		var nom_renseigne = $(this).parents('form').find('input[name=qui]').val();
		var nb_places_renseigne = $(this).parents('form').find('input[name=nb_places]').val();
		var adresse_renseigne = $(this).parents('form').find('input[name=localisation]').val();
		var tel_renseigne = $(this).parents('form').find('input[name=telephone]').val();
		var mail_renseigne = $(this).parents('form').find('input[name=email]').val();

		if(nom_renseigne == ''){
			alert('Vous devez donner un nom (pseudos autorisés).');
		}
		else if(nb_places_renseigne == ''){
			alert('Vous devez donner un nombre de personnes maximum.');
		}
		else if(adresse_renseigne == ''){
			alert('Vous devez donner une localisation (quartier ou arrondissement suffit)');
		}
		else if(tel_renseigne == '' && mail_renseigne == ''){
			alert('Vous devez renseigner au moins un moyen de contact.');
		}
		else{
			$(this).parents('form').submit();
		}
	});

});
