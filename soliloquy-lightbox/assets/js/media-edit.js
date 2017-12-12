/**
* View
*/
var SoliloquyLightBoxView = Backbone.View.extend( {

	/**
    * The Tag Name and Tag's Class(es)
    */
    tagName:    'div',
    className:  'soliloquy-lightbox',

    /**
    * Template
    * - The template to load inside the above tagName element
    */
    template:   wp.template( 'soliloquy-meta-editor-lightbox' ),

    /**
    * Initialize
    */
    initialize: function( args ) {
		
		
        this.model = args.model;

    },

    /**
    * Render
    */
    render: function() {
	
        // Set the template HTML
		this.$el.html( this.template( this.model.attributes ) );
	    
	    this.$el.trigger('render');
	    
	    return this;
	
	}
    
} );

// Add the view to the SoliloquyChildViews, so that it's loaded in the modal
SoliloquyChildViews.push( SoliloquyLightBoxView );