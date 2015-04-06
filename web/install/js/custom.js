$(document).ready(function(){
	// Carousel pour faire défiler les images du produit (Fiche produit)
	$('.slider-for').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		asNavFor: '.slider-nav'
	});
	$('.slider-nav').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		asNavFor: '.slider-for',
		dots: false,
		arrows: false,
		centerMode: true,
		focusOnSelect: true,
		variableWidth: true
	});

	// Permet de faire apparaitre/disparaitre le bloc "Informations de facturation" (Page d'inscription)
	$('#fact_check').click(function(){
		if($(this).attr("value")=="yes"){
			$("#fact_adress").toggle();

			if($(this).is(':checked')) {
				$('#fact_adress input.requiredVal').attr('required', 'true');
				$('#fact_adress select.requiredVal').attr('required', 'true');
			}
			else {
				$('#fact_adress input.requiredVal').removeAttr('required');
				$('#fact_adress select.requiredVal').removeAttr('required');
			}
		}
	});

	// Onglets par etapes (Page de commande)
	var navListItems = $('ul.setup-panel li a'),
		allWells = $('.setup-content');
	allWells.hide();
	navListItems.click(function(e)
	{
		e.preventDefault();
		var $target = $($(this).attr('href')),
			$item = $(this).closest('li');
		
		if (!$item.hasClass('disabled')) {
			navListItems.closest('li').removeClass('active');
			$item.addClass('active');
			allWells.hide();
			$target.show();
		}
	});	
	$('ul.setup-panel li.active a').trigger('click');
	
	$('[id^=activate-step-]').on('click', function(e) {
		var stepNb= parseInt(this.id.slice('activate-step-'.length));
		var stepMinus1= stepNb-1, stepLast= stepNb+1;
		$('ul.setup-panel li:eq('+stepMinus1+')').removeClass('disabled');
		$('ul.setup-panel li a[href="#step-'+stepNb+'"]').trigger('click');

		// Si dernière étape, griser les autre étapes
		//if($('ul.setup-panel li a[href="#step-'+stepLast+'"]').length == 0) {
		//	for (var i = stepMinus1-1; i >= 0; i--) {
		//		$('ul.setup-panel li:eq('+i+')').addClass('disabled');
		//	};
		//}
	});

	// Panel-radio pour les paiements (Page de commande / Paiement)
	$(".radiopanel").click(function() {
    	$(this).children().prop("checked", true);
	});
});