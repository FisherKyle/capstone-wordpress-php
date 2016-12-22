/**
* Handles showing and hiding fields conditionally, based on
* HTML data- attributes
*/
jQuery( document ).ready( function( $ ) {

    // Show/hide elements as necessary when a conditional field is changed
    $( 'input, select' ).conditional( {
        data:               'envira-conditional',
        toggle:             'envira-conditional-toggle',
        value:              'envira-conditional-value',
        displayOnEnabled:   'envira-conditional-display'
    } );

} );

/**
 * jQuery Conditionals 1.0.0
 *
 * Copyright 2015 n7 Studios
 * Released under the MIT license.
 * http://jquery.org/license
 */
(function( $ ) {
    "use strict";

    /**
    * Create .conditional() function
    *
    * @param object options Override Default Settings
    */
    $.fn.conditional = function(options) {
        // Default Settings
        var settings = $.extend({
            data: 'conditional',
            value: 'conditional-value',
            toggle: 'conditional-toggle',
            displayOnEnabled: 'conditional-display'
        }, options);

        // Setup conditionals on each DOM element
        this.each(function() {
            
            // Check for conditional elements
            if ( typeof $( this ).data( settings.data ) === 'undefined' ) {
                return true;
            }

            // Setup vars
            var conditionalElements,
                displayOnEnabled,
                value,
                toggle,
                displayElements;
            
            // Toggle + toggle on change
            $( this ).on( 'change', function() {
                // List the DOM elements to toggle
                conditionalElements = $( this ).data( settings.data ).split(',');

                // Determine whether to display DOM elements when the input is 'enabled'
                displayOnEnabled = $( this ).data( settings.displayOnEnabled );
                if ( typeof displayOnEnabled === 'undefined' ) {
                    displayOnEnabled = true;
                }

                // Determine the value required to display elements
                value = $( this ).data( settings.value );
                if ( typeof value === 'undefined' ) {
                    value = '';
                }

                //
                toggle = $( this ).data( settings.toggle );
                if ( typeof value === 'undefined' ) {
                    toggle = '';
                }

                // By default, don't display elements
                displayElements = false;

                // Determine whether to display relational elements or not
                switch ( $( this ).attr( 'type' ) ) {
                    case 'checkbox':
                        if ( displayOnEnabled ) {
                            displayElements = $( this ).is( ':checked' );
                        } else {
                            displayElements = ( $( this ).is( ':checked' ) ? false : true );
                        }   
                        break;
                    
                    default:
                        console.log( $( this ).attr( 'type' ) );
                        console.log( displayOnEnabled );
                        console.log( 'toggle: ' + toggle );
                        if ( displayOnEnabled ) {
                            if ( value !== '' ) {
                                displayElements = ( ( String( $( this ).val() ) !== String( value ) ) ? false : true );
                            } else {
                                displayElements = ( ( $( this ).val() === '' || $( this ).val() === '0' ) ? false : true ); 
                            }
                        } else {
                            if ( value !== '' ) {
                                displayElements = ( ( $( this ).val() !== value ) ? true : false );
                            } else {
                                displayElements = ( ( $( this ).val() === '' || $( this ).val() === '0' ) ? true : false );
                            }
                        }
                        break;
                }

                // Show/hide elements
                for (var i = 0; i < conditionalElements.length; i++) {
                    if ( displayElements ) {
                        $( '#' + conditionalElements[i] ).fadeIn( 300 );
                        if ( toggle ) {
                            $( '#' + toggle ).fadeOut( 300 );
                        }
                    } else {
                        $( '#' + conditionalElements[i] ).fadeOut( 300 );
                        if ( toggle ) {
                            $( '#' + toggle ).fadeIn( 300 );
                        }
                    }
                }
            });

            // Trigger a change on init so we run the above routine
            $ ( this ).trigger( 'change' );
        });

        // Return DOM elements 
        return this;
    };

})(jQuery);