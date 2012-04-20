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