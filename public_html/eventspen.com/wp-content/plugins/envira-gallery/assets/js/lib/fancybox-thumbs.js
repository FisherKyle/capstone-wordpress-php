 /*!
 * Thumbnail helper for envirabox
 * version: 1.0.7 (Mon, 01 Oct 2012)
 * @requires envirabox v2.0 or later
 *
 * Usage:
 *     $(".envirabox").envirabox({
 *         helpers : {
 *             thumbs: {
 *                 width  : 50,
 *                 height : 50
 *             }
 *         }
 *     });
 *
 */
;(function ($) {
	"use strict";
	//Shortcut for envirabox object
	var F = $.envirabox;

	//Add helper object
	F.helpers.thumbs = {
		defaults : {
			width         : 50,       // thumbnail width
			height        : 50,       // thumbnail height
			mobile_thumbs : false,	  // mobile thumbnails
			mobile_width  : 50, 	  // mobile thumbnail width
			mobile_height : 50,		  // mobile thumbnail height
			position      : 'bottom', // 'top' or 'bottom'
			inline        : false, 	 // if true, positioned to scroll with the content (typically set by the Comments helper)
			dynamicMargin : false,   // set the margins based on the thumbnail height
			dynamicMarginAmount: false,
			source        : function ( item ) {  // function to obtain the URL of the thumbnail image
				var href;

				if (item.element) {
					href = $(item.element).find('img').attr('src');
				}

				if (!href && item.type === 'image' && item.href) {
					href = item.href;
				}

				return href;
			},
			mobileSource        : function ( item ) {  // function to obtain the URL of the thumbnail image
				var href;

				if (item.element) {
					href = $(item.element).find('img').attr('src');
				}

				if (!href && item.type === 'image' && item.href) {
					href = item.href;
				}

				return href;
			}
		},

		wrap  : null,
		list  : null,
		width : 0,

		init: function (opts, obj) {
			var that = this,
				list,
				thumbWidth  = opts.mobile_thumbs && enviraIsMobile() ? opts.mobile_width : opts.width,
				thumbHeight = opts.mobile_thumbs && enviraIsMobile() ? opts.mobile_height : opts.height,
				thumbSource = opts.mobile_thumbs && enviraIsMobile() ? opts.mobileSource : opts.source;

			//Build list structure
			list = '';

			for (var n = 0; n < obj.group.length; n++) {
				list += '<li><a style="width:' + thumbWidth + 'px;height:' + thumbHeight + 'px;" href="javascript:jQuery.envirabox.jumpto(' + n + ');"></a></li>';
			}

			this.wrap = $('<div id="envirabox-thumbs" class="envirabox-thumbs-' + obj.lightboxTheme + '"></div>').addClass(opts.position).appendTo('body');
			this.list = $('<ul>' + list + '</ul>').appendTo(this.wrap);

			//Load each thumbnail
			$.each(obj.group, function (i) {
				var el   = obj.group[ i ],
					href = thumbSource( el );

				if (!href) {
					return;
				}

				$("<img />").load(function () {
					var width  = this.width,
						height = this.height,
						widthRatio, heightRatio, parent;

					if (!that.list || !width || !height) {
						return;
					}

					//Calculate thumbnail width/height and center it
					widthRatio  = width / thumbWidth;
					heightRatio = height / thumbHeight;

					parent = that.list.children().eq(i).find('a');

					if (widthRatio >= 1 && heightRatio >= 1) {
						if (widthRatio > heightRatio) {
							width  = Math.floor(width / heightRatio);
							height = thumbHeight;

						} else {
							width  = thumbWidth;
							height = Math.floor(height / widthRatio);
						}
					}

					$(this).css({
						top    : Math.floor(thumbHeight / 2 - height / 2),
						left   : Math.floor(thumbWidth / 2 - width / 2)
					});

					parent.width(thumbWidth).height(thumbHeight);

					$(this).hide().appendTo(parent).fadeIn(300);

				})
				.attr('src',   href)
				.attr('title', el.title);
			});

			//Set initial width
			// outerWidth(true) doesn't include border width, so we calculate a single thumbnail's width manually
			// Old code is commented out.
			var thumb = this.list.children().eq(0),
				thumb_link = $('a', $(thumb));

			// Link left border + link right border + li left margin + li right margin + li width = thumbnail width
			this.width = parseInt( thumb_link.css('border-left-width') ) + parseInt( thumb_link.css('border-left-width') ) + parseInt( thumb.css('margin-left') ) + parseInt( thumb.css('margin-right') ) + parseInt( thumb.css('width') ); 
			
			this.list.width(this.width * obj.group.length).css('left', Math.floor($(window).width() * 0.5 - (obj.index * this.width + this.width * 0.5)));
			
			//this.width = this.list.children().eq(0).outerWidth(true);
			//this.list.width(this.width * (obj.group.length + 1)).css('left', Math.floor($(window).width() * 0.5 - (obj.index * this.width + this.width * 0.5)));
		},

		beforeLoad: function (opts, obj) {
			//Remove self if gallery does not have at least two items
			if (obj.group.length < 2) {
				obj.helpers.thumbs = false;

				return;
			}

		},

		beforeShow: function(opts, obj) {
			var margin = [0,0,0,0];

			if (this.list) {
				this.onUpdate(opts, obj);
			} else {
				this.init(opts, obj);

				//Increase bottom margin to give space for thumbs
				if(opts.dynamicMargin) {
					opts.dynamicMarginAmount = opts.dynamicMarginAmount === false ? 75 : opts.dynamicMarginAmount;
					margin[ opts.position === 'top' || opts.position === 'top has-other-content' ? 0 : 2 ] = this.list.parent().height() + opts.dynamicMarginAmount;
				}
				else {
					margin[ opts.position === 'top' || opts.position === 'top has-other-content' ? 0 : 2 ] = this.list.parent().height();
				}

				$.extend(obj.margin, {
					'thumbs': margin
				});

			}

			// If set to inline, add a class now
			if ( opts.inline ) {
				this.wrap.addClass( 'inline' );
			}

			//Set active element
			this.list.children().removeClass('active').eq(obj.index).addClass('active');

			if ( enviraIsMobile() ) {
				this.list.addClass('mobile');
			}
		},

		//Center list
		onUpdate: function (opts, obj) {
			if (this.list) {
				var wWidth = $(window).width(), 
					wHeight = $(window).height(), 
					thumb = this.list.children().eq(0),
					thumb_link = $('a', $(thumb)),
					margin = [0,0,0,0];

				if(opts.dynamicMargin) {
					opts.dynamicMarginAmount = opts.dynamicMarginAmount === false ? 75 : opts.dynamicMarginAmount;
					margin[ opts.position === 'top' || opts.position === 'top has-other-content' ? 0 : 2 ] = this.list.parent().height() + opts.dynamicMarginAmount;
				}
				else {
					margin[ opts.position === 'top' || opts.position === 'top has-other-content' ? 0 : 2 ] = this.list.parent().height();
				}

				$.extend(obj.margin, {
					'thumbs': margin
				});

				this.width = parseInt( thumb_link.css('border-left-width') ) + parseInt( thumb_link.css('border-left-width') ) + parseInt( thumb.css('margin-left') ) + parseInt( thumb.css('margin-right') ) + parseInt( thumb.css('width') );

				this.list.width(this.width * obj.group.length);
				this.list.stop(true).animate({
					'left': Math.floor($(window).width() * 0.5 - (obj.index * this.width + this.width * 0.5))
				}, 150);

			}
		},

		beforeClose: function () {
			if (this.wrap) {
				this.wrap.remove();
			}

			this.wrap  = null;
			this.list  = null;
			this.width = 0;
		}
	};

}(jQuery));