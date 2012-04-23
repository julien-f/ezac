$(document).ready(function(){


	//Afficher / masquer le formulaire de demande
	$('.bouton_afficher_form_demande').click(function(e){
		e.preventDefault();
		
		form = $(this).parents('.proposition').find('.form_demande');
		if($(form).css('marginTop') == '10px'){
			$(form).animate({marginTop: '-280px'});
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
		
		var mail_renseigne = $(this).parents('form').find('input[name=email]').val();
		
        var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
 
        
        if((mail_renseigne == '') || (nom_renseigne == '')) {
            
            hasError = true;
            alert("Un champ est manquant : renseignez au moins votre nom et votre email");
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
