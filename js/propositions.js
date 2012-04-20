$(document).ready(function(){


	//Afficher / masquer le formulaire de demande
	$('.bouton_afficher_form_demande').click(function(e){
		e.preventDefault();
		
		form = $(this).parents('.proposition').find('.form_demande');
		if($(form).css('marginTop') == '10px'){
			$(form).animate({marginTop: '-180px'});
			$(this).html('Demander une place');
		}
		else{
			$(form).animate({marginTop: '10px'});
			$(this).html('Annuler');
		}
	});
	
	//Soumission du formulaire de demande
	$('.bouton_valider_form_demande').click(function(a){
		a.preventDefault();
		var nom_renseigne = $(this).parents('form').find('input[name=qui]').val();
		var tel_renseigne = $(this).parents('form').find('input[name=telephone]').val();
		var mail_renseigne = $(this).parents('form').find('input[name=email]').val();
		
		if(nom_renseigne == ''){
			alert('Vous devez donner un nom (pseudos autorisés).');
		}
		else if(tel_renseigne == '' && mail_renseigne == ''){
			alert('Vous devez renseigner au moins un moyen de contact.');
		}
		else{
			$(this).parents('form').submit();
		}
	});

});