/**
* Loading plugin for jQuery
* 
* Small helper to give the user a visual feedback that something is happening 
* when fetching/posting data
* 
* USAGE:
* - global overlay:                     $.isLoading();
* - use javascript:                     $( selector ).isLoading();
* - On non-form elements:               $("div").isLoading({ text: "Loading", position:'inside'});
* - remove the loading element:         $( selector ).isLoading( "hide" );
*
* @author Laurent Blanes <laurent.blanes@gmail.com>
* ---
* Copyright 2013, Laurent Blanes ( https://github.com/hekigan/is-loading )
* 
* The MIT License (MIT)
* 
* Copyright (c) 2013 Laurent Blanes
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/
;(function ( $, window, document, undefined ) {
    // Create the defaults once
    var pluginName = "isLoading",
        defaults = {
            'position': "right",        // right | inside | overlay
            'text': "",                 // Text to display next to the loader
            'class': "icon-refresh",    // loader CSS class
            'tpl': '<span class="isloading-wrapper %wrapper%">%text%<i class="%class% icon-spin"></i></span>',    // loader base Tag
            'disableSource': true,      // true | false
            'disableOthers': []
        };
    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        
        // Merge user options with default ones
        this.options = $.extend( {}, defaults, options );
        this._defaults     = defaults;
        this._name         = pluginName;
        this._loader       = null;                // Contain the loading tag element
        this.init();
    }
    // Contructor function for the plugin (only once on page load)
    function contruct() {
        if ( !$[pluginName] ) {
            $.isLoading = function( opts ) {
                $( "body" ).isLoading( opts );
            };
        }
    }
    
    Plugin.prototype = {
        
        init: function() {
            
            if( $( this.element ).is( "body") ) {
                this.options.position = "overlay";
            }
            this.show();
        },
        show: function() {
            var tpl = this.options.tpl.replace( '%wrapper%', ' isloading-show ' + ' isloading-' + this.options.position );
            tpl = tpl.replace( '%class%', this.options['class'] );
            tpl = tpl.replace( '%text%', ( this.options.text !== "" ) ? this.options.text + ' ' : '' );
            this._loader = $( tpl );
            
            // Disable the element
            if( $( this.element ).is( "input, textarea" ) && true === this.options.disableSource ) {
                $( this.element ).attr( "disabled", "disabled" );
            }
            else if( true === this.options.disableSource ) {
                $( this.element ).addClass( "disabled" );
            }
            // Set position
            switch( this.options.position ) {
                case "inside":
                    $( this.element ).html( this._loader );
                    break;
                case "overlay":
                    if( $( this.element ).is( "body") ) {
                        $( "body" ).prepend( '<div class="isloading-overlay" style="position:fixed; left:0; top:0; z-index: 10000; background: rgba(0,0,0,1); width: 100%; height: ' + $( this.element ).outerHeight() + 'px;" />' );
                    }
                    else {
                        var cssPosition = $( this.element ).css('position');
                        var pos = null;
                        
                        if( 'relative' === cssPosition ) {
                            pos = { 'top': 0,  'left': 0 };
                        } else {
                            pos = $( this.element ).position();
                        }
                        $( this.element ).prepend( '<div class="isloading-overlay" style="position:absolute; top: ' + pos.top + 'px; left: ' + pos.left + 'px; z-index: 10000; background: rgba(0,0,0,1); width: ' + $( this.element ).outerWidth() + 'px; height: ' + $( this.element ).outerHeight() + 'px;" />' );
                    }
                    $( ".isloading-overlay" ).html( this._loader );
                    break;
                default:
                    $( this.element ).after( this._loader );
                    break;
            }
            this.disableOthers();
        },
        hide: function() {
            if( "overlay" === this.options.position ) {
                $( ".isloading-overlay" ).remove();
            } else {
                $( this._loader ).remove();
                $( this.element ).text( $( this.element ).attr( "data-isloading-label" ) );
            }
            $( this.element ).removeAttr("disabled").removeClass("disabled");
            this.enableOthers();
        },
        disableOthers: function() {
            $.each(this.options.disableOthers, function( i, e ) {
                var elt = $( e );
                if( elt.is( "button, input, textarea" ) ) {
                    elt.attr( "disabled", "disabled" );
                }
                else {
                    elt.addClass( "disabled" );
                }
            });
        },
        enableOthers: function() {
            $.each(this.options.disableOthers, function( i, e ) {
                var elt = $( e );
                if( elt.is( "button, input, textarea" ) ) {
                    elt.removeAttr( "disabled" );
                }
                else {
                    elt.removeClass( "disabled" );
                }
            });
        }
    };
    
    // Constructor
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if ( options && "hide" !== options || !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
            } else {
                var elt = $.data( this, "plugin_" + pluginName );
                if( "hide" === options )    { elt.hide(); }
                else                        { elt.show(); }
            }
        });
    };
    
    contruct();
})( jQuery, window, document );