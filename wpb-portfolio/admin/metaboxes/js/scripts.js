jQuery(function($) {
	
	/**
	 * Gallery Meta
	 * Added by WpBean
	 */
	

	$('.wpb-field-gallery').each(function() {

		var $this   = $(this),
		$edit   = $this.find('.wpb-edit'),
		$remove = $this.find('.wpb-remove'),
		$list   = $this.find('ul'),
		$input  = $this.find('input'),
		$img    = $this.find('img'),
		wp_media_frame,
		wp_media_click;

		$this.on('click', '.wpb-add, .wpb-edit', function( e ) {

			var $el   = $(this),
			what  = ( $el.hasClass('wpb-edit') ) ? 'edit' : 'add',
			state = ( what === 'edit' ) ? 'gallery-edit' : 'gallery-library';

			e.preventDefault();

			// Check if the `wp.media.gallery` API exists.
			if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
				return;
			}

			// If the media frame already exists, reopen it.
			if ( wp_media_frame ) {
				wp_media_frame.open();
				wp_media_frame.setState(state);
				return;
			}

			// Create the media frame.
			wp_media_frame = wp.media({
				library: {
				type: 'image'
				},
				frame: 'post',
				state: 'gallery',
				multiple: true
			});

			// Open the media frame.
			wp_media_frame.on('open', function() {

				var ids = $input.val();

				if ( ids ) {

					var get_array = ids.split(',');
					var library   = wp_media_frame.state('gallery-edit').get('library');

					wp_media_frame.setState(state);

					get_array.forEach(function(id) {
						var attachment = wp.media.attachment(id);
						library.add( attachment ? [ attachment ] : [] );
					});

				}
			});

			// When an image is selected, run a callback.
			wp_media_frame.on( 'update', function() {

				var inner  = '';
				var ids    = [];
				var images = wp_media_frame.state().get('library');

				images.each(function(attachment) {

					var attributes = attachment.attributes;
					var thumbnail  = ( typeof attributes.sizes.thumbnail !== 'undefined' ) ? attributes.sizes.thumbnail.url : attributes.url;

					inner += '<li><img src="'+ thumbnail +'"></li>';
					ids.push(attributes.id);

				});

				$input.val(ids).trigger('change');
				$list.html('').append(inner);
				$remove.removeClass('hidden');
				$edit.removeClass('hidden');

			});

			// Finally, open the modal.
			wp_media_frame.open();
			wp_media_click = what;

		});

		// Remove image
		$remove.on('click', function( e ) {
			e.preventDefault();
			$list.html('');
			$input.val('').trigger('change');
			$remove.addClass('hidden');
			$edit.addClass('hidden');
		});

	});



	/**
	 * the upload image button, saves the id and outputs a preview of the image
	 */
	

	var imageFrame;
	$('.meta_box_upload_image_button').click(function(event) {
		event.preventDefault();
		
		var options, attachment;
		
		$self = $(event.target);
		$div = $self.closest('div.meta_box_image');
		
		// if the frame already exists, open it
		if ( imageFrame ) {
			imageFrame.open();
			return;
		}
		
		// set our settings
		imageFrame = wp.media({
			title: 'Choose Image',
			multiple: false,
			library: {
		 		type: 'image'
			},
			button: {
		  		text: 'Use This Image'
			}
		});
		
		// set up our select handler
		imageFrame.on( 'select', function() {
			selection = imageFrame.state().get('selection');
			
			if ( ! selection )
			return;
			
			// loop through the selected files
			selection.each( function( attachment ) {
				console.log(attachment);
				var src = attachment.attributes.sizes.full.url;
				var id = attachment.id;
				
				$div.find('.meta_box_preview_image').attr('src', src);
				$div.find('.meta_box_upload_image').val(id);
			} );
		});
		
		// open the frame
		imageFrame.open();
	});
	
	// the remove image link, removes the image id from the hidden field and replaces the image preview
	$('.meta_box_clear_image_button').click(function() {
		var defaultImage = $(this).parent().siblings('.meta_box_default_image').text();
		$(this).parent().siblings('.meta_box_upload_image').val('');
		$(this).parent().siblings('.meta_box_preview_image').attr('src', defaultImage);
		return false;
	});
	
	// the file image button, saves the id and outputs the file name
	var fileFrame;
	$('.meta_box_upload_file_button').click(function(e) {
		e.preventDefault();
		
		var options, attachment;
		
		$self = $(event.target);
		$div = $self.closest('div.meta_box_file_stuff');
		
		// if the frame already exists, open it
		if ( fileFrame ) {
			fileFrame.open();
			return;
		}
		
		// set our settings
		fileFrame = wp.media({
			title: 'Choose File',
			multiple: false,
			library: {
		 		type: 'file'
			},
			button: {
		  		text: 'Use This File'
			}
		});
		
		// set up our select handler
		fileFrame.on( 'select', function() {
			selection = fileFrame.state().get('selection');
			
			if ( ! selection )
			return;
			
			// loop through the selected files
			selection.each( function( attachment ) {
				console.log(attachment);
				var src = attachment.attributes.url;
				var id = attachment.id;
				
				$div.find('.meta_box_filename').text(src);
				$div.find('.meta_box_upload_file').val(src);
				$div.find('.meta_box_file').addClass('checked');
			} );
		});
		
		// open the frame
		fileFrame.open();
	});
	
	// the remove image link, removes the image id from the hidden field and replaces the image preview
	$('.meta_box_clear_file_button').click(function() {
		$(this).parent().siblings('.meta_box_upload_file').val('');
		$(this).parent().siblings('.meta_box_filename').text('');
		$(this).parent().siblings('.meta_box_file').removeClass('checked');
		return false;
	});
	
	// function to create an array of input values
	function ids(inputs) {
		var a = [];
		for (var i = 0; i < inputs.length; i++) {
			a.push(inputs[i].val);
		}
		//$("span").text(a.join(" "));
    }
	// repeatable fields
	$('.meta_box_repeatable_add').live('click', function() {
		// clone
		var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
		var clone = row.clone();
		clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
		clone.find('input.regular-text, textarea, select').val('');
		clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
		row.after(clone);
		// increment name and id
		clone.find('input, textarea, select')
			.attr('name', function(index, name) {
				return name.replace(/(\d+)/, function(fullMatch, n) {
					return Number(n) + 1;
				});
			});
		var arr = [];
		$('input.repeatable_id:text').each(function(){ arr.push($(this).val()); }); 
		clone.find('input.repeatable_id')
			.val(Number(Math.max.apply( Math, arr )) + 1);
		if (!!$.prototype.chosen) {
			clone.find('select.chosen')
				.chosen({allow_single_deselect: true});
		}
		//
		return false;
	});
	
	$('.meta_box_repeatable_remove').live('click', function(){
		$(this).closest('tr').remove();
		return false;
	});
		
	$('.meta_box_repeatable tbody').sortable({
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		handle: '.hndle'
	});
	
	// post_drop_sort	
	$('.sort_list').sortable({
		connectWith: '.sort_list',
		opacity: 0.6,
		revert: true,
		cursor: 'move',
		cancel: '.post_drop_sort_area_name',
		items: 'li:not(.post_drop_sort_area_name)',
        update: function(event, ui) {
			var result = $(this).sortable('toArray');
			var thisID = $(this).attr('id');
			$('.store-' + thisID).val(result) 
		}
    });

	$('.sort_list').disableSelection();

	// turn select boxes into something magical
	if (!!$.prototype.chosen)
		$('.chosen').chosen({ allow_single_deselect: true });



	/**
	 * Shortcode Select on focus
	 */

	$("input.the-shortcode").focus(function() { $(this).select(); } );
});