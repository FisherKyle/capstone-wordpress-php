 /*!
 * Slideshow helper for envirabox
 * version: 1.1.0 (Mon, 15 Oct 2012)
 * @requires envirabox v2.0 or later
 *
 * Usage:
 *     $(".envirabox").envirabox({
 *         helpers : {
 *             slideshow: {
 *                 position : 'top'
 *             }
 *         }
 *     });
 *
 */
;(function ($) {
	//Shortcut for envirabox object
	var F = $.envirabox;

	//Add helper object
	F.helpers.slideshow = {
		defaults : {
			skipSingle : false, // disables if gallery contains single image
		},

		slideshow: null,

		beforeLoad: function (opts, obj) {
			//Remove self if gallery do not have at least two items
			if (opts.skipSingle && obj.group.length < 2) {
				obj.helpers.slideshow = false;
				return;
			}

		},

		onPlayStart: function () {
			if (this.slideshow) {
				this.slideshow.play.attr('title', 'Pause slideshow').addClass('btnPlayOn').parent().addClass('playing');
			}
		},

		onPlayEnd: function () {
			if (this.slideshow) {
				this.slideshow.play.attr('title', 'Start slideshow').removeClass('btnPlayOn').parent().removeClass('playing');
			}
		},

		onReady: function (opts, obj) {
			var slideshow = this.slideshow;

			slideshow = {
				play   : $('.btnPlay').click( F.play )
			}

			if ( F.player.isActive ) {
				slideshow.play.attr('title', 'Pause slideshow').addClass('btnPlayOn').parent().addClass('playing');
			}

			this.slideshow = slideshow;
		},

		beforeClose: function () {
			this.slideshow = null;
		}
	};

}(jQuery));