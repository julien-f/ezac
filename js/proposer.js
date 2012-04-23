$(document).ready(function(){

	//Soumission du formulaire de demande
	$('.bouton_valider_form_proposition').click(function(a){
		a.preventDefault();
		
		var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        
        
		var nom_renseigne = $(this).parents('form').find('input[name=qui]').val();
		var nb_places_renseigne = $(this).parents('form').find('input[name=nb_places]').val();
		
		
		var localisation_renseigne = $(this).parents('form').find('input[name=localisation]').val();
		var tel_renseigne = $(this).parents('form').find('input[name=telephone]').val();
		var mail_renseigne = $(this).parents('form').find('input[name=email]').val();
		
				
		if(nom_renseigne == ''){
			hasError = true;
			alert('Vous devez donner un nom (pseudos autorisés).');
		}
		else if(nb_places_renseigne == ''){
			hasError = true;
			alert('Vous devez donner un nombre de personnes maximum.');
		}
		else if(localisation_renseigne == ''){
			hasError = true;
			alert('Vous devez donner une localisation (la ville ou le quartier suffit)');
		}
		else if(mail_renseigne == ''){
			hasError = true;
			alert('Vous devez renseigner au moins votre email');
		}
		else if(!emailReg.test(mail_renseigne)) {
            hasError = true;
            alert(mail_renseigne+" ne semble pas être un email conforme");
        }
 
        if(hasError == true) { return false; }
		else{
			$(this).parents('form').submit();
		}
	});

});
