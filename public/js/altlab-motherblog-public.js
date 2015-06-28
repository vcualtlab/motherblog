(function($) {
    'use strict';
    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note that this assume you're going to use jQuery, so it prepares
     * the $ function reference to be used within the scope of this
     * function.
     *
     * From here, you're able to define handlers for when the DOM is
     * ready:
     *
     * $(function() {
     *
     * });
     *
     * Or when the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and so on.
     *
     * Remember that ideally, we should not attach any more than a single DOM-ready or window-load handler
     * for any particular page. Though other scripts in WordPress core, other plugins, and other themes may
     * be doing this, we should try to minimize doing that in our own work.
     */
    // console.log('mallowmarsh');

    // console.log($('#state'));

    // $('#state').on('change', function() {
    //     console.log('butter');
    //     console.log($(this));
    //     // foo is the id of the other select box 
    //     if ( $(this).val() === 'notinoz' ) {
    //         $('#foo').show();
    //     } else {
    //         $('#foo').hide();
    //     }
    // });

	$(function() {
	    
	    $("input[name=have-rampages]:radio").change(function() {
	        if ( $(this).val() == 'Yes') {
	            $('#have-rampages-yes').show();
	            $('#want-rampages').hide();

	            $('#want-rampages-yes').hide();
	            $('#have-blog').hide();
	            $('#have-blog-yes').hide();
	            $('#have-blog-no').hide();

	        } else {
	        	$('#have-rampages-yes').hide();
	            $('#want-rampages').show();
	        	
	        	$('#want-rampages-yes').hide();
	            $('#have-blog').hide();
	            $('#have-blog-yes').hide();
	            $('#have-blog-no').hide();
	        }
	    });

	   	$("input[name=want-rampages]:radio").change(function() {
	        if ( $(this).val() == 'Yes') {
	            $('#want-rampages-yes').show();
	            $('#have-blog').hide();

	            $('#have-blog-yes').hide();
	            $('#have-blog-no').hide();
	        } else {
	        	$('#want-rampages-yes').hide();
	            $('#have-blog').show();

	            $('#have-blog-yes').hide();
	            $('#have-blog-no').hide();
	        }
	    });

	    $("input[name=have-blog]:radio").change(function() {
	        if ( $(this).val() == 'Yes') {
	            $('#have-blog-yes').show();
	            $('#have-blog-no').hide();
	        } else {
	        	$('#have-blog-yes').hide();
	            $('#have-blog-no').show();
	        }
	    });

	});

})(jQuery);