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
    

    function isUrlValid(url) {
        return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    };
    function submitIfUrlIsValid(url){
    	if ( isUrlValid( url ) ) {
            $('#submit').show();
        } else {
            $('#submit').hide();
        }
    };

    $(function() {
        $("input[name=have-rampages]:radio").change(function() {
            if ($(this).val() == 'Yes') {
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
                $('#blog-select').val('');
                $('#submit').hide();
            }
        });
        $("input[name=want-rampages]:radio").change(function() {
            if ($(this).val() == 'Yes') {
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
            if ($(this).val() == 'Yes') {
                $('#have-blog-yes').show();
                $('#have-blog-no').hide();

                submitIfUrlIsValid( $('#blog-feed').val() );
            } else {
                $('#have-blog-yes').hide();
                $('#have-blog-no').show();
                $('#submit').hide();
            }
        });
        $('#blog-select').change(function() {
            if ($(this).val() != '') {
                $('#submit').show();
            } else {
                $('#submit').hide();
            }
        });
        $('#blog-feed').each(function() {
            // Save current value of element
            $(this).data('oldVal', $(this));
            // Look for changes in the value
            $(this).bind("propertychange keyup input cut paste", function(event) {
                // If value has changed...
                if ($(this).data('oldVal') != $(this).val()) {
                    // Updated stored value
                    $(this).data('oldVal', $(this).val());
                    
                    submitIfUrlIsValid( $(this).val() );
                }
            });
        });
        $('#altlab-motherblog-subscribe').on('keyup keypress', function(e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
})(jQuery);