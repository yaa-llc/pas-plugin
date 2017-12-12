jQuery(function($){


	//portfolio selected category
	$('body').on('click','#wpb_fp_filter_select ul li', function(){
		$(this).parents('#wpb_fp_filter_select').find('#wpb-fp-sort-portfolio span').html($(this).html());
	});

	//portfolio sort
	$("#wpb_fp_filter_select").hover(function () {
	    $(".wpb_fp_filter_Select").slideToggle(500);
	});

	//$('a[data-post-id="438"]').trigger('click');
	//$('a.wpb_fp_preview').trigger('click');


	/**
	 * Mixitup trigger
	 */
	
	$("[data-mix]").each(function(){
		var $self = $(this),
		$filt = $self.find("[data-filter]"),
		$mix  = $($self.data("mix"));

		$mix.mixItUp({ 
			animation: {
				duration: 1000,
				effects: 'fade stagger(34ms) translateY(10%) scale(0.01)',
				easing: 'cubic-bezier(0.6, -0.28, 0.735, 0.045)',
			},
			selectors: {
				filter: $filt,
			}
		});

	});



	/**
	 * Tooltipster trigger
	 */
	 
	if ( $.isFunction($.fn.tooltipster) ) {
		$('.filter').tooltipster({
		   animation: 'grow',
		   delay: 200,
		   theme: 'tooltipster-punk',
		   touchDevices: false,
		   trigger: 'hover',
		   minWidth: 40,
		});
	}
	
}); // Non conflict