/**
 * UMBG-Slider jQuery Plugin v1.4
 * http://codecanyon.net/user/theefarmer?ref=theefarmer
 * http://theefarmer.com
 *
 * Author: TheeFarmer | Alendee Internet Solutions
 * Copyright: (c) 2016 Alendee Internet Solutions. All rights reserved.
 *
 * Last Change: 2016-07-04 - Fix panRight effect.
 *              2015-12-04 - Add autoPlay.
 *              2015-10-18 - Fix transitioning of slides when a decimal number is use (Math.floor(convertSeconds).
 *                         - Enhance code format.
 *              2015-09-22 - Add transition timing option.
 *                         - Enhance image transition fade.
 *              2015-05-25 - Initial release
 **/

(function ( $ ) {
	'use strict';
	
	$.fn.umbgslider = function ( options ) {
		
		var opts = $.extend( {}, $.fn.umbgslider.defaults, options );
		
		return this.each( function () {
			
			var currentIndex = 0,
				items = $( '.umbg-slider-container div' ),
				itemAmt = items.length,
				item,
				slideTransform,
				slidePlaying = 1,
				restarted = 0,
				tt = 0,
				remainingTime,
				sliderInterval,
				slide = $( '.umbg-slider-container div' ).eq( currentIndex ),
				transitionTiming = opts.transition,
				startTime = opts.duration,
				convertSeconds = (startTime + transitionTiming) / 1000,//Add back the transition time to get full duration.
				computedStyle,
				idx = itemAmt - 1
			;
			
			// Start slider with slides hidden.
			$( '.umbg-slider-container div' ).hide();
			
			// Find the current slide and load animation effect.
			function cycleItems() {
				
				// Find previous slide index and fade out.
				var prevItem = $( '.umbg-slider-container div' ).eq( idx );
				prevItem.css( { 'transition': 'all ' + (transitionTiming / 1000) + 's' } ).fadeOut( transitionTiming ).css( { 'z-index': '0' } );
				
				// Get current slide and fade in.
				item = $( '.umbg-slider-container div' ).eq( currentIndex );
				item.fadeIn( transitionTiming ).css( { 'z-index': '1' } );
				
				// Load animation effect.
				switch ( opts.effect ) {
					
					case 'panRight':
						panRight( item );
						
						break;
					case 'panLeft':
						panLeft( item );
						break;
					case 'panUp':
						panUp( item );
						break;
					case 'panDown':
						panDown( item );
						break;
					case 'zoomIn':
						zoomIn( item );
						break;
					case 'zoomOut':
						zoomOut( item );
						break;
					case 'random':
						var effs = [ zoomOut, zoomIn, panRight, panLeft, panUp, panDown ];
						effs[ Math.floor( Math.random() * effs.length ) ]( item );
						break;
					default:
						fade( item );
						break;
				} // End switch.
				
			} // End cycleItems().
			
			cycleItems();
			
			// Run the slider.
			function runSlider() {
				
				sliderInterval = setInterval( function () {
					
					// If slide has been restarted then set tt to remaining time of slide.
					if ( restarted === 1 ) {
						tt = remainingTime;
						restarted = 0;
					}
					tt ++;
					
					// If tt is greater than the converted seconds then tt equals 1.
					if ( tt > convertSeconds ) {
						tt = 1;
					}
					
					idx = currentIndex;
					
					// Set the current slide index.
					// Math.floor is used to remove decimal from convertSeconds.
					if ( tt === Math.floor( convertSeconds ) && slidePlaying === 1 ) {
						
						currentIndex += 1;
						
						// Set settings if looping and/or rewind.
						if ( opts.loop === 0 && opts.rewind === 0 ) {
							
							if ( currentIndex > itemAmt - 1 ) {
								currentIndex = 0;
								
							} else if ( currentIndex === itemAmt - 1 ) {
								
								setTimeout( function () {
									opts.endSliderFunction();
								}, startTime - 100 );
								
							}
							
						} else if ( currentIndex > (itemAmt - 1) && opts.loop === 0 && opts.rewind === 1 ) {
							
							currentIndex = 0;
							
							setTimeout( function () {
								opts.endSliderFunction();
							}, 1000 );
							
						} else if ( currentIndex > (itemAmt - 1) && opts.loop === 1 && opts.rewind === 0 ) {
							
							currentIndex = 0;
							
							opts.endSliderFunction();
							
						}
						
						// If only one slide then don't run cycleItems.
						if ( itemAmt > 1 ) {
							
							cycleItems();
						}
					}
					
				}, 1000 );
				
			} // End runSlider().
			
			// If more than one slide then run slider else just run once & check for auto playback.
			if ( itemAmt > 1 && opts.autoPlay === 1 ) {
				
				runSlider();
				
			} else if ( itemAmt > 1 && opts.autoPlay === 0 ) {
				
				runSlider();
				setTimeout( function () {
					stop();
				}, transitionTiming );
				
			}
			else {
				
				setTimeout( function () {
					stop();
					opts.endSliderFunction();
				}, startTime - 100 );
				
			}
			
			// Variables for getMatrixValues().
			var scaleX,
				skewX,
				skewY,
				scaleY,
				translateX,
				translateY,
				angle,
				slideW,
				slideH,
				style1
			;
			
			// When the slider pauses we get matrix values to re-insert into the code to resume the slide where it left off.
			function getMatrixValues( s ) {
				slide = s || item;
				
				var tr = slide.css( "-webkit-transform" ) ||
					slide.css( "-moz-transform" ) ||
					slide.css( "-ms-transform" ) ||
					slide.css( "-o-transform" ) ||
					slide.css( "transform" ) ||
					"FAIL";
				
				// rotation matrix - http://en.wikipedia.org/wiki/Rotation_matrix
				// var values = tr.split( '(' )[ 1 ].split( ')' )[ 0 ].split( ',' );
				var values = tr.split( '(' )[ 1 ];
				values = values.split( ')' )[ 0 ];
				values = values.split( ',' );
				
				scaleX = values[ 0 ];
				skewX = values[ 1 ];
				skewY = values[ 2 ];
				scaleY = values[ 3 ];
				translateX = values[ 4 ].trim();
				translateY = values[ 5 ].trim();
				
				//var scale = Math.sqrt( scaleX * scaleX + skewX * skewX );
				// arc sin, convert from radians to degrees, round
				//var sin = skewX / scale;
				// next line works for 30deg but not 130deg (returns 50);
				// var angle = Math.round(Math.asin(sin) * (180/Math.PI));
				angle = Math.round( Math.atan2( skewX, scaleX ) * (180 / Math.PI) );
				
				slideW = slide.width() * scaleX;
				slideH = slide.height() * scaleY;
				
			}
			
			var effectEasing = opts.easingEffect,
				trans = {
					'webkitTransition': 'all ' + convertSeconds + 's ' + effectEasing,
					'mozTransition'   : 'all ' + convertSeconds + 's ' + effectEasing,
					'msTransition'    : 'all ' + convertSeconds + 's ' + effectEasing,
					'oTransition'     : 'all ' + convertSeconds + 's ' + effectEasing,
					'transition'      : 'all ' + convertSeconds + 's ' + effectEasing
				}
			;
			
			function fade( element ) {
				
				$( element ).addClass( 'notrans' ).css( {
					'webkitTransform': 'scale(1) translate(0, 0)',
					'msTransform'    : 'scale(1) translate(0, 0)',
					'transform'      : 'scale(1) translate(0, 0)'
				} );
				
				slideTransform = 'scale(1) translate(0, 0)';
				
			}// End fade().
			
			function panRight( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1.3) translate(-10%, 0)',
					'msTransform'    : 'scale(1.3) translate(-10%, 0)',
					'transform'      : 'scale(1.3) translate(-10%, 0)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1.3) translate(0,0)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
					
				}, transitionTiming );
				//}, 100 );
				
			}// End panRight().
			
			function panLeft( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1.3) translate(10%, 0)',
					'msTransform'    : 'scale(1.3) translate(10%, 0)',
					'transform'      : 'scale(1.3) translate(10%, 0)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1.3) translate(0,0)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
					
				}, transitionTiming );
				//}, 100 );
				
			} // End panLeft().
			
			function panUp( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1.3) translate(0, 10%)',
					'msTransform'    : 'scale(1.3) translate(0, 10%)',
					'transform'      : 'scale(1.3) translate(0, 10%)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1.3) translate(0,0)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
				}, transitionTiming );
				
			}// End panUp().
			
			function panDown( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1.3) translate(0, -10%)',
					'msTransform'    : 'scale(1.3) translate(0, -10%)',
					'transform'      : 'scale(1.3) translate(0, -10%)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1.3) translate(0,0)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
				}, transitionTiming );
				
				
			}// End panDown().
			
			function zoomIn( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1) rotate(0deg)',
					'msTransform'    : 'scale(1) rotate(0deg)',
					'transform'      : 'scale(1) rotate(0deg)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1.2) rotate(2deg)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
				}, transitionTiming );
				
			}// End zoomIn().
			
			function zoomOut( element ) {
				
				$( element ).css( {
					'webkitTransform': 'scale(1.2)  rotate(2deg)',
					'msTransform'    : 'scale(1.2)  rotate(2deg)',
					'transform'      : 'scale(1.2)  rotate(2deg)'
				} ).addClass( 'notrans' );
				
				slideTransform = 'scale(1) rotate(0deg)';
				
				setTimeout( function () {
					$( element ).css( {
						'webkitTransform': slideTransform,
						'msTransform'    : slideTransform,
						'transform'      : slideTransform
					} ).removeClass( 'notrans' ).css( trans );
				}, transitionTiming );
				
			}// End zoomOut().
			
			function stop() {
				clearInterval( sliderInterval );
				getMatrixValues();
				slidePlaying = 0;
				remainingTime = tt;
				computedStyle = 'scale(' + scaleX + ') rotate(' + angle + 'deg) translate(' + (translateX / slideW) * 100 + '%,' + (translateY / slideH) * 100 + '%)';
				slide.css( {
					'webkitTransform': computedStyle,
					'mozTransform'   : computedStyle,
					'msTransform'    : computedStyle,
					'oTransform'     : computedStyle,
					'transform'      : computedStyle
				} );
			}
			
			function start() {
				
				if ( slidePlaying === 0 ) {
					
					restarted = 1;
					slidePlaying = 1;
					runSlider();
					
					slide.css( trans ).css( {
						'webkitTransform': slideTransform,
						'mozTransform'   : slideTransform,
						'msTransform'    : slideTransform,
						'oTransform'     : slideTransform,
						'transform'      : slideTransform
					} );
					
				}
			}
			
			$.fn.umbgslider.pause = function () {
				if ( itemAmt > 1 ) {
					stop();
				}
			};
			
			$.fn.umbgslider.play = function () {
				if ( itemAmt > 1 ) {
					start();
				}
			};
			
		} );// End return this.each().
		
		
	};// End .fn.umbgslider.
	
	$.fn.umbgslider.defaults = {
		'duration'         : 6000, // Duration of each slide in milliseconds.
		'effect'           : 'fade', // random, fade, panRight, panLeft, panUp, panDown, zoomIn, zoomOut.
		'transition'       : 2000, // Duration of fade between slides in milliseconds.
		'easingEffect'     : 'ease-in-out', // ease-in-out, ease, ease-in, ease-out, linear.
		'order'            : 'normal', // normal, reverse, random.
		'loop'             : 0, // Loop playback, 1=yes 0=no.
		'rewind'           : 0, // rewind only works if looping is off.
		'autoPlay'         : 1,
		//'fullScreen': 0, // When being used as full screen slider.
		//'startButton': '#umbg-slider-start',
		//'stopButton': '#umbg-slider-stop',
		'endSliderFunction': function () {
		}
	};
	
})( jQuery );