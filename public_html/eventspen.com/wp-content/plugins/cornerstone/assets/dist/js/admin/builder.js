(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.CS_builder = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = Cornerstone.Mn.Behavior.extend({

	defaults: {
		message: cs.l18n( 'confirm-message' ),
		subtext: false,
		yep: cs.l18n( 'confirm-yep' ),
		nope: cs.l18n( 'confirm-nope' ),
		classes: []
	},

	initialize: function() {
		this.listenTo( this.view, 'confirm:warn:open', this.open );
		this.listenTo( cs.confirm, 'accept', this.accept );
		this.listenTo( cs.confirm, 'decline', this.decline );
	},

	events: function() {
		var events = {};
		var ui = this.options.ui || 'confirmWarn';
		events[ 'click @ui.' + ui ] = 'open';
		return events;
	},

	open: function() {
		cs.confirm.trigger( 'open', _.extend( this.options, { view: this.view } ) );
	},

	accept: function( viewID ) {
		if ( viewID != this.view.cid ) return;
		this.view.triggerMethod( 'confirm:warn:accept' );
	},

	decline: function( viewID ) {
		if ( viewID != this.view.cid ) return;
		this.view.triggerMethod( 'confirm:warn:decline' );
	}

} );

},{}],2:[function(require,module,exports){
module.exports = Cornerstone.Mn.Behavior.extend({

	initialize: function() {
		this.listenTo( this.view, 'confirm:open', this.open );
	},

	events: function() {
		var events = {};
		var ui = this.options.ui || 'confirm';
		events[ 'click @ui.' + ui ] = 'open';
		return events;
	},

	open: function() {
		cs.confirm.trigger( 'open', _.extend( this.options, {
			allowQuickConfirm: this.view.canQuickConfirm,
			accept: _.bind( this.accept, this ),
			decline: _.bind( this.decline, this )
		} ), this.view );
	},

	accept: function() {
		this.view.triggerMethod( 'confirm:accept' );
	},

	decline: function() {
		this.view.triggerMethod( 'confirm:decline' );
	}

} );

},{}],3:[function(require,module,exports){
module.exports.Confirm = require( './confirm' );
module.exports.ConfirmWarn = require( './confirm-warn' );

},{"./confirm":2,"./confirm-warn":1}],4:[function(require,module,exports){
var Mousetrap = require( 'mousetrap' );
require( 'mousetrap/plugins/global-bind/mousetrap-global-bind' );
Cornerstone.Vendor.Mousetrap = Mousetrap;
Cornerstone.Vendor.NProgress = require( 'nprogress' );
Cornerstone.Vendor.dragula = require( '../vendor/dragula' );
require( 'perfect-scrollbar/jquery' )( Backbone.$ );
require( '../vendor/pointer-events-polyfill' );
require( '../vendor/rgbaster' );
require( '../vendor/jquery.growl' );
require( '../vendor/jquery.visible' );
Cornerstone.Vendor.HTMLHint = require( '../vendor/htmlhint' ).HTMLHint;
require( './utility/jquery.shadow-height' );
require( './utility/string-replace-all' );

/**
 * Fire it up
 */
cs.registerComponents( require( './components' ) );
cs.updateConfig( csBuilderData );
cs.updateRegistry( {
	start: [
		'mn-extensions',
		'template-loader',
		'view-loader',
		'model-loader',
		'keybindings',
		'builder'
	],
	editor: [
		'editor',
		'post-handler',
		'options',
		'navigator',
		'element-pane',
		'template-manager',
		'layout',
		'layout-templates',
		'inspector',
		'settings',
		'skeleton',
		'element-manager',
		'cheatsheet'
	],
	preview: [ 'preview', 'render-queue' ]
} );

},{"../vendor/dragula":185,"../vendor/htmlhint":186,"../vendor/jquery.growl":187,"../vendor/jquery.visible":188,"../vendor/pointer-events-polyfill":189,"../vendor/rgbaster":190,"./components":25,"./utility/jquery.shadow-height":94,"./utility/string-replace-all":96,"mousetrap":199,"mousetrap/plugins/global-bind/mousetrap-global-bind":200,"nprogress":201,"perfect-scrollbar/jquery":202}],5:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.data     = Backbone.Radio.channel( 'cs:data' );
		cs.extra    = Backbone.Radio.channel( 'cs:extra' );
		cs.observer = Backbone.Radio.channel( 'cs:observer' );
		cs.tooltips = Backbone.Radio.channel( 'cs:tooltips' );
		cs.search   = Backbone.Radio.channel( 'cs:search' );
		cs.confirm  = Backbone.Radio.channel( 'cs:confirm' );
		cs.message  = Backbone.Radio.channel( 'cs:message' );

		this.cs.loadComponents( this.cs.config( 'isPreview' ) ? 'preview' : 'editor'  );

		this.browserDetection();

		document.onmousemove = function( e ) {
			cs.mouse = _.pick( e, 'clientX', 'clientY', 'pageX', 'pageY' );
		};
	},

	browserDetection: function() {
		var b = Cornerstone.Vendor.bowser;

		if ( b.msie ) {
			Backbone.$( 'body' ).addClass( 'cs-browser-msie cs-browser-msie-' + parseInt( b.version ) );
		}

		if ( b.msedge ) {
			Backbone.$( 'body' ).addClass( 'cs-browser-msedge cs-browser-msedge-' + parseInt( b.version ) );
		}

	}

} );

},{}],6:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	bindings: {
		'ark': 'up up down down left right left right b a enter'
	},

	initialize: function( options ) {

		cs.keybind = Backbone.Radio.channel( 'cs:keybind' );

		// Wait until cs.global is ready
		this.listenTo( cs.events, 'editor:init', this.setup );
		this.listenTo( cs.events, 'preview:iframe:reloaded', this.setup );

		this.common();
	},

	setup: function() {

		// Resend under specific channel
		this.listenTo( cs.global, 'keybind', function( action ) {
			cs.keybind.trigger( action );
		});

		this.bindings = _.extend( cs.config( 'keybindings' ), this.bindings );

		_.each( this.bindings, function( sequence, action ) {

			var type = undefined;
			var types = ['keypress', 'keyup', 'keydown' ];

			_.each( types, function( prefix ) {
				if ( 0 == sequence.indexOf( prefix + ':' ) ) {
					type = prefix;
					sequence = sequence.replace( prefix + ':', '' );
				}
			});

			Cornerstone.Vendor.Mousetrap.bindGlobal( sequence, function() {
				cs.global.trigger( 'keybind', action ); // Send everywhere
			}, type );

		});

	},

	common: function() {

	this.listenTo( cs.keybind, 'delete-confirm', function() {
		cs.data.reply( 'delete:confirm:key', true );
		cs.events.trigger( 'delete:confirm:key', true );
	});

	this.listenTo( cs.keybind, 'delete-release', function() {
		cs.data.reply( 'delete:confirm:key', false );
		cs.events.trigger( 'delete:confirm:key', false );
	});

	}

} );

},{}],7:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		/**
		 * Load Behaviors and set Mn lookup location
		 */

		this.Behaviors = require( '../../behaviors' );
		Cornerstone.Mn.Behaviors.behaviorsLookup = _.bind( function() {
			return this.Behaviors;
		}, this );

	}

} );

},{"../../behaviors":3}],8:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	modelLookup: function( id ) {
		return this.Models[id] || this.Models.Base;
	},

	collectionLookup: function( id ) {
		return this.Collections[id] || this.Collections.Base;
	}

} );

},{}],9:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.render = this;
		this.cache = {};
		this.jobs = {};
		this.timing = {};

		var delay = parseInt( cs.config( 'remoteRenderDelay' ), 10 );
		this.run = _.debounce( _.bind( this.run, this ), delay );

		// If nothing is queued in a reasonable amount of time,
		// consider the page primed
		var noRender = setTimeout( _.bind( function() {
			this.trigger( 'primed' );
		}, this ), delay * 2 );

		this.once( 'add', function() {
			clearTimeout( noRender );
		});

		this.on( 'hold', function( state ) {
			this.holdRender = !! state;
			if ( false === state ) this.run();
		} );

	},

	onQueue: function( model ) {

		var flags = model.definition.get( 'flags' );
		this.trigger( 'add', model );
		var data = model.toJSON();

		var _transient = {};

		if ( flags.child ) {
			var parent = model.collection.getParent();
			if ( parent )
				_transient.parent = parent.getDataForChild();
		}

		if ( ! _.isEmpty( _transient ) )
			data._transient = _transient;

		this.queue( model.cid, flags._v, data, function( response ) {
			this.cache[model.cid] = response;
			model.trigger( 'remote:render' );
		});

	},

	preRender: function( type ) {

		var key = 'new:' + type;
		if ( this.cache[key] ) return;

		var el = cs.elementLibrary.lookup( type );

		var newModel = new Cornerstone.Models.Element( { _type: type } );
		var data = newModel.toJSON();
		delete newModel;

		this.queue( key, el.get( 'flags' )._v, data, function( response ) {
			this.cache[key] = response;
		});

	},

	shadow: function( model, original ) {
		var cache = this.cache[original];
		if ( cache ) {
			this.cache[model.cid] = ( _.isFunction( cache ) ) ? cache : _.clone( cache );
		}
	},

	getCache: function( model ) {
		var cache = this.cache[model.cid];
		if ( ! cache ) {
			this.triggerMethod( 'queue', model );
			cache = cs.template( 'loading' );
		}

		return cache;
	},

	queue: function( id, provider, data, callback ) {

		var timestamp = Date.now();

		this.timing[id] = timestamp;
		this.jobs[ id ] = { data: data, provider: provider, ts: timestamp, callback: callback };

		if ( this.holdRender ) return;

		this.run();

	},

	run: function( data ) {

		if ( ! data ) {

			var batch = _.map( this.jobs, function( value, key ) {
				return { jobID: key, ts: value.ts, provider: value.provider, data: value.data || {} };
			});

			data = {
				post_id: cs.post.get( 'post_id' ),
				batch: batch
			};

			if ( cs.raw_markup || ( top && top.cs && top.cs.raw_markup ) )
				data['raw_markup'] = true;

			this.registeredJobs = _.clone( this.jobs );
			this.jobs = {};

		}

		var request = cs.ajax( 'cs_render_element', data, {

			success: _.bind( function( response ) {

				var handlers = [];

				if ( response.scripts ) {
					handlers = handlers.concat( _.map( response.scripts, function( script, key ) {
						return function( next ) {
							cs.preview.trigger( 'late:script:enqueue', key, script, next );
						};
					}, this ) );
				}

				if ( response.styles ) {
					handlers = handlers.concat( _.map( response.styles, function( style, key ) {
						return function( next ) {
							cs.preview.trigger( 'late:style:enqueue', key, style, next );
						};
					}, this ) );
				}

				handlers = handlers.concat( _.map( response.jobs, function( job, jobID ) {

					return _.bind( function( next ) {

						if ( job.ts < this.timing[jobID] ) {
							next(); return;
						}

						if ( 0 === job.markup.indexOf( '%%TMPL%%' ) ) {
							job.markup = job.markup.replace( '%%TMPL%%', '' );
							job.markup = _.template( job.markup );
						}

						if ( '' === job.markup ) {
							job.markup = cs.template( 'empty-element' );
						}

						if ( this.registeredJobs[ jobID ] && ! this.jobs[ jobID ] ) {
							this.registeredJobs[ jobID ].callback.call( this, job.markup );
							delete this.registeredJobs[ jobID ];
						}

						next();

					}, this );

				}, this ) );

				Cornerstone.serial( handlers ).done( _.bind( function( message ) {

					this.trigger( 'primed' );

					if ( message )
						console.log( message );

				}, this ) );

			}, this )
		} );

	},

	isRunning: function() {
		return _.keys( this.registeredJobs ).length > 0;
	}

} );

},{}],10:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		this.cs.loadTemplates( ( this.cs.Config.isPreview ) ? require( '../../templates/elements' ) : require( '../../templates' ) );
		this.listenTo( cs.events, 'editor:init', this.populateIcons );

	},

	populateIcons: function() {

		cs.config( 'fontAwesome' );
		var iconList = {};

		_.each( _.pairs( cs.Config.fontAwesome ), function( item ) {
			iconList[item[1]] = iconList[item[1]] || new Array;
			iconList[item[1]].push( item[0] );
		} );

    this.icons = iconList;
		cs.data.reply( 'get:icons', this.icons );
	}

} );

},{"../../templates":67,"../../templates/elements":58}],11:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {
		this.listenTo( cs.events, 'editor:init', this.registerControlViews );
		this.listenTo( cs.events, 'editor:init', this.registerSkeletonViews );
		this.listenTo( cs.events, 'preview:init', this.registerElementViews );
	},

	registerElementViews: function() {

		window.Cornerstone.ElementViews = {
			Base: require( '../../views/preview/base' )
		};

		_.extend( window.Cornerstone.ElementViews, require( '../../views/preview' ) );
		cs.events.trigger( 'register:element:views' );

	},

	registerSkeletonViews: function() {

		window.Cornerstone.SkeletonViews = { Base: require( '../../views/skeleton/base' ) };
		_.extend( window.Cornerstone.SkeletonViews, require( '../../views/skeleton' ) );
		cs.events.trigger( 'register:skeleton:views' );

	},

	registerControlViews: function() {

		window.Cornerstone.ControlViews = { Base: require( '../../views/controls/base' ) };
		_.extend( window.Cornerstone.ControlViews, require( '../../views/controls' ) );
		cs.events.trigger( 'register:control:views' );

	},

	elementLookup: function( id ) {
		return Cornerstone.ElementViews[id] || Cornerstone.ElementViews.Base;
  },

  skeletonLookup: function( id ) {
		return Cornerstone.SkeletonViews[id] || Cornerstone.SkeletonViews.Base;
  }

} );

},{"../../views/controls":111,"../../views/controls/base":97,"../../views/preview":168,"../../views/preview/base":162,"../../views/skeleton":181,"../../views/skeleton/base":179}],12:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.data.reply( 'cheatsheet:data', false );
		// this.listenTo( cs.global, 'editor:ready', this.fetchSheets );

	},

	fetchSheets: function() {

		cs.ajax( 'cs_cheatsheet', {
			post_id: this.cs.post.get( 'post_id' )
		}, {
			success: function( response ) {
				if ( response && response.sheets && _.isArray( response.sheets ) ) {
					cs.data.reply( 'cheatsheet:data', response.sheets );
				}
			}
		} );

	}

} );

},{}],13:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( options ) {

		cs.global = Backbone.Radio.channel( 'cs:remote' );
		this.listenToOnce( cs.global, 'preview:primed', this.primed );

		// Defer views until window and iFrame are loaded.
		Backbone.$( window ).on( 'load', _.bind( this.loadView, this ) );
		Backbone.$( '#preview-frame' ).on( 'load', _.bind( this.loadIFrame, this ) );

		Backbone.$( window ).resize( function( e ) {
			cs.events.trigger( 'editor:resize', e );
		} );

		cs.events.trigger( 'editor:init' );

		this.listenTo( cs.elements, 'element:deleted', function() {
			cs.global.trigger( 'kill:observer' );
		} );

	},

	primed: function( late ) {

		this.clearPreloader();

		require( '../../utility/custom-media-manager' );

		cs.global.reply( 'editor:ready', true );
		cs.global.trigger( 'editor:ready' );

		if ( late )
			cs.message.trigger( 'notice', cs.l18n( 'preview-late' ) );

	},

	loadView: function() {
		var EditorView = require( '../../views/main/editor' );
		this.view = new EditorView({ el: '#editor' });
		this.view.render();
	},

	loadIFrame: function() {

		var frameWindow = document.getElementById( 'preview-frame' ).contentWindow;

		if ( frameWindow.cs && frameWindow.cs.preview ) {
			frameWindow.cs.preview.trigger( 'iframe:ready', this, cs );
			return;
		}

		console.log( 'Unable to initialize preview. iFrame failed to load.' );

		_.defer( function() {
			cs.global.trigger( 'preview:primed' );
			cs.global.trigger( 'preview:failure', cs.l18n( 'preview-failure1' ) );
		} );

	},

	clearPreloader: function() {

		$preloader = Backbone.$( '#preloader' ).addClass( 'cs-preloader-fade' ).one( 'transitionend', function() {
			$preloader.remove();
		} );

		// Fallback when 'transitionend' isn't available
		_.delay( function() {
			if ( $preloader ) $preloader.remove();
		}, 2000 );

	}

} );

},{"../../utility/custom-media-manager":92,"../../views/main/editor":154}],14:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( ) {
		cs.elementLibrary.registerContext( 'builder', this.elementFilter );
	},

	elementFilter: function( child ) {

		// Hide inactive or out-of-context
		var flags = child.get( 'flags' );
		if ( false == child.get( 'active' ) || ! _.contains( ['builder', 'all'], flags.context ) || flags.child  )
			return false;

		return true;
	}

} );

},{}],15:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( ) {
		this.listenTo( cs.global, 'element:delete', this.elDelete );
		this.listenTo( cs.global, 'element:erase', this.erase );
		this.listenTo( cs.global, 'element:duplicate', this.duplicate );
	},

	elDelete: function( model, opts ) {

		var opts = opts || {};

		if ( model.collection && model.collection.parent && model.collection.parent.atFloor() ) {

			if ( opts.noConfirm ) {
				elReset();
			} else {
				cs.confirm.trigger( 'open', {
					message: cs.l18n( 'sortable-at-floor' ),
					accept: elReset,
					allowQuickConfirm: true
				});
			}

			return;
		}

		if ( opts.noConfirm ) {
			elDestroy();
		} else {

			var message = ( model.elements && model.elements.length > 0 )  ? 'confirm-element-delete-contents' : 'confirm-element-delete';

			cs.confirm.trigger( 'open', {
				message: cs.l18n( message ).replace( '%s', model.definition.get( 'ui' ).title ),
				accept: elDestroy,
				allowQuickConfirm: true
			});
		}

		function elDestroy() {
			_.defer( function() {
				cs.elements.trigger( 'delete', { model: model } );
			});
		}

		function elReset() {
			_.defer( function() {

				// This should look more like:
				// cs.elements.trigger( 'add:item', root, { _type: 'row' } );
				// ...allowing a title to be derrived here, or any other attributes.
				cs.elements.trigger( 'add:item', model.get( '_type' ), model.collection.parent );
				cs.elements.trigger( 'delete', { model: model } );

			});
		}
	},

	erase: function( model, opts ) {
		var opts = opts || {};
		if ( opts.noConfirm ) {
			elDestroy();
		} else {
			cs.confirm.trigger( 'open', {
				message: cs.l18n( 'columns-erase-confirm' ),
				accept: elDestroy,
				allowQuickConfirm: true
			});
		}

		function elDestroy() {
			_.defer( function() {
				cs.elements.trigger( 'erase', { model: model } );
			});
		}
	},

	duplicate: function( model ) {
		cs.elements.trigger( 'duplicate', model );
	}

} );

},{}],16:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		this.inspect = {
			primary: new Cornerstone.InspectionSupervisor(),
			secondary: new Cornerstone.InspectionSupervisor(),
			expansion: new Cornerstone.InspectionSupervisor()
		};

		this.resetInspector( 'primary' );

		this.listenTo( cs.events, 'inspect:element', this.inspectElement );

		this.listenTo( cs.events, 'expand:control', this.expansionSource );
		this.listenTo( cs.events, 'expand:close', this.expansionClose );

		cs.navigate.reply( 'inspector:heading', cs.l18n( 'inspector-heading' ) );

	},

	inspectElement: function( model, navigate, scroll ) {

		var navigate = _.isUndefined( navigate ) ? true : navigate;
		var scroll = scroll || true;

		var primary = model;
		var secondary = false;

		if ( model.definition.get( 'flags' ).child ) {

			var parent = model.collection.getParent();

			primary = ( ! parent.definition ) ? false : parent;
			secondary = model;
		}

		if ( primary ) {
			this.updateInspector( 'primary', primary );
			if ( navigate ) cs.navigate.trigger( 'inspector:home' );
			if ( scroll ) cs.global.trigger( 'autoscroll', primary.cid );
		}

		if ( cs.config( 'debug' ) ) cs.nowInspecting = primary;

		if ( secondary ) {
			this.updateInspector( 'secondary', secondary );
			if ( navigate ) cs.navigate.trigger( 'inspector:item' );
		}

	},

	resetInspector:function( mode ) {

		if ( 'primary' == mode ) {
			this.resetInspector( 'secondary' );
			this.setHeading( false );
		}

		this.updateInspector( mode, new Cornerstone.Models.Proxyable(), [], [] );

	},

	expansionSource: function( source, name ) {

		this.updateInspector( 'expansion', source, _.filter( source.definition.getControls(), function( control ) {
			return ( name == control.name );
		} ) );

		cs.events.trigger( 'expand:control:open' );

	},

	expansionClose: function() {
		this.inspect.expansion.reset();
	},

	updateInspector: function( level, source, controls, metaControls ) {

		if ( ! this.inspect[level] ) return;

		var timer;
		this.listenToOnce( source, 'imminent:replacement', function( newSource ) {
			this.listenToOnce( newSource, 'created', function( model ) {
				this.inspectElement( model, false );
				clearTimeout( timer );
			});
		} );

		this.listenToOnce( source, 'destroy', function( newSource ) {
			timer = setTimeout( _.bind( function() {
				this.resetInspector( level, null );
			}, this ), 5 );
		} );

		if ( ! controls )
			controls = source.definition.getControls();

		if ( ! metaControls )
			metaControls = this.getMetaControls( level, source );

		this.inspect[level].source( {
			name: 'meta',
			source: source,
			controls: metaControls
		} );

		this.inspect[level].source( {
			name: 'element',
			source: source,
			controls: controls
		} );

		this.inspect[level].rebuildControls();

		if ( 'primary' == level && source.definition ) {
			this.setHeading( source.definition.get( 'ui' ).title );
		}

	},

	getMetaControls: function( level, source ) {

		var controls = [];

		var ui = source.definition.get( 'ui' );
		var name = source.definition.get( 'name' );
		var internal = ( '_internal' == source.definition.get( 'flags' ).context );

		if ( 'primary' == level ) {

			if ( 'settings' !== cs.navigate.request( 'active:pane' ) ) {
				controls.push({ type: 'breadcrumbs' });
			}

			if ( ui.helpText ) {
				controls.push( {
					type: 'info-box',
					ui: {
						title: ui.helpText.title || '',
						message: ui.helpText.message || ''
					}
				} );
			}

			if ( ! internal ) {
				var actionType = ( _.contains( [ 'section', 'row', 'column' ], name ) ) ? name : 'element';
				controls.push( { type: actionType + '-actions' } );
			}

		}

		return controls;
	},

	setHeading: function( heading ) {

		if ( ! heading )
			heading = cs.l18n( 'inspector-heading' );

		cs.navigate.reply( 'inspector:heading', heading );
		cs.navigate.trigger( 'refresh:inspector:heading' );

	},

	getPrimaryControls: function() {
		return this.inspect.primary.controls;
	},

	getSecondaryControls: function() {
		return this.inspect.secondary.controls;
	},

	getExpansionControls: function() {
		return this.inspect.expansion.controls;
	}

});

},{}],17:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	nativeSections: [ 'themeco-pages', 'themeco-blocks' ],
	userSections: [ 'user-pages', 'user-blocks' ],

	initialize: function() {

		this.data = new Backbone.Model({
			title: cs.l18n( 'templates-title' )
		});

		this.controls = new Cornerstone.Collections.Control();
		this.listenTo( cs.templates, 'ready', this.setup );
		this.listenTo( cs.templates, 'add', this.setup );
		this.listenTo( cs.templates, 'remove', this.setup );

	},

	setup: function() {

		var controls = new Cornerstone.Collections.Control();

		controls.add({
			name: 'info',
			type: 'info-box',
			ui: {
				title: cs.l18n( 'templates-info-title' ),
				message: cs.l18n( 'templates-info-description' )
			}
		});

		controls.add({
			name: 'action',
			type: 'template-actions',
			ui: {
				divider: true
			}
		});

		controls.add({
			name: 'title',
			type: 'template-save-dialog',
			condition: { 'action': 'save' }
		});

		controls.add({
			name: 'uploader',
			type: 'template-upload-dialog',
			condition: { 'action': 'upload' }
		});

		var name, choices, type, userTemplates, templateManager;

		templateManager = cs.component( 'template-manager' );
		userTemplates = [];

		_.each( _.union( this.nativeSections, this.userSections ), function( sectionName ) {

			var section = templateManager.templates.section( sectionName );

			choices = section.map( _.bind( function( item ) {
				if ( _.contains( this.userSections, sectionName ) )
					userTemplates.push( item );
				return { value: item.get( 'slug' ), label: item.get( 'title' ) };
			}, this ) );

			type = ( sectionName.indexOf( 'page' ) > -1 ) ? 'page' : 'block';

			controls.add({
				name: sectionName,
				type: 'template-select',
				ui: {
					title: cs.l18n( 'templates-' + sectionName ),
					buttonText: cs.l18n( 'templates-insert' ),
					divider: ( 'block' == type ),
					compact: ( 'block' == type )
				},
				options: {
					choices: choices,
					templateType: type
				}
			});

		}, this );

		choices = userTemplates.map( function( item ) {
			var format = ( 'block' == item.get( 'type' ) ) ? cs.l18n( 'templates-remove-block' ) : cs.l18n( 'templates-remove-page' );
			return { value: item.get( 'slug' ), label: format.replace( '%s', item.get( 'title' ) ) };
		});

		controls.add({
			name: 'user-removals',
			type: 'template-remove',
			ui: {
				title: cs.l18n( 'templates-remove-label' ),
				buttonText: cs.l18n( 'templates-remove' )
			},
			options: {
				choices: choices,
				templateType: 'remove'
			}
		});

		controls.invoke( 'setProxy', this.data );

		var oldControls = this.controls;

		this.controls = controls;
		cs.templates.trigger( 'control:reset' );

		oldControls.reset();

	}

} );

},{}],18:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		this.inspect = {};

		this.inspect.primary = new Cornerstone.InspectionSupervisor( [ {
			name: 'primary',
			source: new Cornerstone.Models.Proxyable(),
			controls: [ {
				name: 'help_text',
				key: 'disabled',
				type: 'info-box',
				ui: {
					title: cs.l18n( 'layout-info-title' ),
					message: cs.l18n( 'layout-info-description' )
				}
			}, {
				name: 'layout_actions',
				key: 'disabled',
				type: 'layout-actions',
				options: {
					divider: true
				}
			} ]
		}, {
			name: 'section',
			source: cs.post.data,
			controls: [ {
				name: 'sections',
				key: 'elements',
				type: 'sortable-sections'
			}]
		} ] );

		this.inspect.secondary = new Cornerstone.InspectionSupervisor( [ {
			name: 'section',
			source: new Cornerstone.Models.Proxyable(),
			controls: []
		}, {
			name: 'row',
			source: new Cornerstone.Models.Proxyable(),
			controls: []
		} ] );

		cs.navigate.reply( 'layout:active:row', false );
		this.listenTo( cs.events, 'inspect:layout', this.inspectorDetector );

		this.listenTo( cs.events, 'add:section', function() {
			cs.elements.trigger( 'add:item', 'section', cs.post.data, cs.l18n( 'section-numeric' ) );
		});

		// If a user forgets to add a new section before begining an element drag,
		// add a new transient section to catch their incoming element.
		this.listenTo( cs.global, 'incoming:element', function( type ) {
			if ( cs.post.data.elements.isEmpty() ) {
				cs.events.trigger( 'add:section' );
				this.listenToOnce( cs.global, 'incoming:element:end', function( type ) {

					try {
						var first = cs.post.data.elements.first();
						if ( first.elements.first().elements.first().elements.isEmpty() ) {
							cs.elements.trigger( 'delete', { model: first } );
						}
					} catch ( e ) {

					}
				});
			}
		});

	},

	inspectSection: function( section, activeRow, after ) {

		if ( ! section ) return;
		if ( ! activeRow ) activeRow = section.elements.first();

		cs.global.trigger( 'autoscroll', activeRow.cid );

		cs.navigate.reply( 'layout:active:row', activeRow );

		this.inspect.secondary.source( {
			name: 'section',
			source: section,
			controls: section.definition.getControls( '_layout' )
		} );

		this.inspect.secondary.source( {
			name: 'row',
			source: activeRow,
			controls: activeRow.definition.getControls( '_layout' )
		} );

		this.inspect.secondary.rebuildControls();

		if ( _.isFunction( after ) ) after();
	},

	inspectRow: function( row ) {
		if ( ! row ) return;
		this.inspectSection( row.collection.parent, row );
	},

	inspectorDetector: function( model, options ) {

		var options = options || {};
		var type = model.get( '_type' );

		switch ( model.get( '_type' ) ) {
			case 'section':
				this.inspectSection( model, null, after );
				break;
			case 'row':
				this.inspectSection( model.collection.parent, model, after );
				break;
			case 'column':
				this.inspectSection( model.collection.parent.collection.parent, model.collection.parent, after );
				break;
		}

		function after() {
			if ( options.navigate ) cs.navigate.trigger( 'layout:rows' );
    }

	}

} );

},{}],19:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	panes: {
		layout: [ 'rows', 'templates'],
		elements: [],
		inspector: [ 'item' ],
		settings: [ 'item']
	},

	initialize: function() {

		cs.navigate = Backbone.Radio.channel( 'cs:navigate' );

		cs.navigate.reply( 'auto:focus', false );

		this.listenTo( cs.navigate, 'pane', this.killObserver );
		this.listenTo( cs.navigate, 'subpane:opened', this.killObserver );

		this.listenTo( cs.navigate, 'pane:switch', function() {
			cs.extra.trigger( 'flyout', 'none' );
		});

		this.listenTo( cs.global, 'auto:focus', _.debounce( function( key ) {
			cs.navigate.reply( 'auto:focus', key );
			cs.navigate.trigger( 'auto:focus', key );
		}, 10, true ) );

		this.listenTo(  cs.global, 'inspect', function( model ) {
			cs.events.trigger( 'inspect:element', model, true );
			cs.confirm.trigger( 'abort' );
		});

		cs.extra.reply( 'get:collapse', false );

    // Propogate navigation shortcuts
		_.each( this.panes, function( subs, pane ) {

			this.listenTo( cs.navigate, pane + ':home', function( selected ) {
				if ( cs.extra.request( 'get:collapse' ) ) return;
				cs.navigate.trigger( 'pane', pane );
			});

			_.each( subs, function( sub ) {
				this.listenTo( cs.navigate, pane + ':' + sub, function( selected ) {
					if ( cs.extra.request( 'get:collapse' ) ) return;
					cs.navigate.trigger( 'pane', pane, sub );
				});
			}, this );

		}, this );

		this.listenTo( cs.global, 'nav:kylelements', function() {
			cs.navigate.trigger( 'elements:home' );
		});

	},

	killObserver: function() {
		 cs.global.trigger( 'kill:observer' );
	}

} );

},{}],20:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.options = Backbone.Radio.channel( 'cs:options' );

		cs.options.reply( 'help:text', ( 'false' == localStorage['cs_options_help_text'] ) ? false : true );
		cs.options.reply( 'skeleton:mode', ( 'true' == localStorage['cs_options_skeleton_mode'] ) );

		this.listenTo( cs.extra, 'toggle', function( item ) {

			var toggled = ! cs.options.request( item );

			cs.options.reply( item, toggled );
			cs.options.trigger( item, toggled );

			localStorage.setItem( 'cs_options_' + item.replaceAll( ':', '_' ), ( toggled ) ? 'true' : 'false' );

		});

	}

} );

},{}],21:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		this.cs.post = new Cornerstone.Post( cs.config( 'post' ) );
		this.listenTo( cs.events, 'action:save', this.save );
		this.listenTo( cs.data, 'save:success', this.saveSuccess );
		this.listenTo( cs.data, 'save:error', this.saveError );
		this.listenTo( cs.global, 'preview:primed', function() {
			this.listenTo( cs.channel, 'page:unsaved', function() {
				cs.changed = true;
			} );
		} );

    cs.data.reply( 'saved:last', null );

		// Warn before closing browser window
		window.onbeforeunload = function( e ) {
			if ( cs.changed ) {
				return cs.l18n( 'home-onbeforeunload' );
			}
		};

	},

	saveSuccess: function() {

		cs.data.reply( 'saved:last', Cornerstone.Vendor.moment() );
		cs.channel.trigger( 'update:saved:last' );
		cs.changed = false;

		if ( 'settings' == cs.navigate.request( 'active:pane' ) ) {

			localStorage.CornerstonePane = 'settings';
			location.reload();

			// Removed: cs.events.trigger('refresh:preview');

    }

	},

	save: function() {

		cs.data.trigger( 'save' );

		Cornerstone.Vendor.NProgress.configure({ showSpinner: false, speed: 850, minimum: 0.925 });
		Cornerstone.Vendor.NProgress.start();

		cs.ajax( 'cs_endpoint_save', _.omit( this.cs.post.toJSON(), [ '_type' ] ), {
			success: function( response ) {
				cs.data.trigger( 'save:success', response );
			},
			error: function( response ) {
				cs.data.trigger( 'save:error', response );
			},
			always: function( response ) {
				Cornerstone.Vendor.NProgress.done();
			}
		} );

	}

} );

},{}],22:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {
		cs.data.reply( 'settings:ready', false );

		this.listenTo( cs.data, 'save', this.save );

		this.listenTo( cs.global, 'editor:ready', this.fetchSections );
		this.listenTo( cs.global, 'settings:pingback', this.setupListeners );

	},

	save: function() {
		if ( true == cs.data.request( 'settings:ready' ) )
			this.cs.post.set( 'settings', this.getSettingData() );
	},

	fetchSections: function() {

		cs.ajax( 'cs_setting_sections', {
			post_id: this.cs.post.get( 'post_id' )
		}, {
			success: _.bind( this.loadSettings, this )
		} );

	},

	loadSettings: function( response ) {

		this.inspect = new Cornerstone.InspectionSupervisor( {
			name: 'primary',
			source: new Cornerstone.Models.Proxyable(),
			controls: [ {
				name: 'settings-actions',
				key: 'disabled',
				type: 'settings-actions'
			} ]
	  } );

		this.cs.settingSections = new Backbone.Collection( response.models );
		this.settings = new Cornerstone.Collections.Setting( response.data );

		var general = this.settings.findWhere( { _section: 'general' } );

		if ( general ) {

			//
			// Auto publish brand new pages
			//

			if ( window.localStorage.getItem( 'cornerstone_auto_draft' ) === cs.post.data.get( 'post_id' ).toString() ) {
				localStorage.removeItem( 'cornerstone_auto_draft' );

				if ( 'draft' === general.get( 'post_status' ) ) {
					general.set( 'post_status', 'publish' );
				}

			}
		}

		this.settings.each( function( setting ) {

			setting.inspect = new Cornerstone.InspectionSupervisor( {
				name: 'primary',
				source: setting,
				controls: setting.section.get( 'controls' )
			} );

		} );

		cs.data.reply( 'settings:ready', true );
		cs.global.trigger( 'settings:ready' );

  },

	getSettings: function() {
		return this.settings;
	},

	getSettingData: function() {
		this.settings.invoke( 'updateChildData' );
		return this.settings.toJSON();
	},

	getPrimaryControls: function() {
		return this.inspect.controls;
	},

	setupListeners: function() {

		var model = this.settings.findWhere( { _section: 'general' } );

		if ( model ) {

			if ( window.localStorage.getItem( 'cornerstone_auto_draft' ) === cs.post.data.get( 'post_id' ).toString() ) {
				localStorage.removeItem( 'cornerstone_auto_draft' );

				if ( 'draft' === model.get( 'post_status' ) ) {
					console.log("MATCH");
					model.set( 'post_status', 'publish' );
				}

			}

			this.listenTo( model, 'change:custom_css', function( model, value ) {
				cs.global.trigger( 'update:custom_css', value );
			});

			cs.global.trigger( 'update:custom_css', model.get( 'custom_css' ) );

		} else {
			cs.warn( 'Unable to load Custom CSS because Cornerstone settings are corrupted.' );
		}

		model = this.settings.findWhere( { _section: 'responsive-text' } );

		if ( model ) {

			var update = _.debounce( function() {

				cs.global.trigger( 'update:responsive_text', _.map( model.elements.toProxy().toJSON(), function( item ) {
					return _.pick( item, 'selector', 'compression', 'min_size', 'max_size' );
				}) );

			}, 5 );

			this.listenTo( model.elements, 'model:change', update );
			this.listenTo( model.elements, 'add', update );
			this.listenTo( model.elements, 'remove', update );
			update();

		} else {
			cs.warn( 'Unable to load Responsive Text because Cornerstone settings are corrupted. ' );
		}

	}

} );

},{}],23:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.data.reply( 'skeleton:preview:height', Backbone.$( window ).height() );
		this.enabled = cs.options.request( 'skeleton:mode' ) || false;
		this.toggle( this.enabled, true );

		this.listenTo( cs.keybind, 'skeleton-mode', function() {
			cs.extra.trigger( 'toggle', 'skeleton:mode' );
		} );

		this.listenTo( cs.options, 'skeleton:mode', function( state ) {
			this.toggle( state || undefined );
		} );

		this.listenTo( cs.global, 'preview:resize', function( dimensions ) {
			var height = ( dimensions.Body.height > 1 ) ? Math.min( dimensions.Window.height, dimensions.Body.height ) : dimensions.Window.height;
			cs.data.reply( 'skeleton:preview:height', height );
			cs.events.trigger( 'resize:skeleton' );
		});

		this.listenTo( cs.global, 'preview:failure', function( message ) {
			this.toggle( true );
			this.locked = true;
			cs.message.trigger( 'error', message );
			Backbone.$( 'body' ).addClass( 'cs-recovery-mode' );
		} );

	},

	toggle: function( state, silent ) {

		if ( this.locked ) {
			cs.message.trigger( 'warning', cs.l18n( 'skeleton-locked' ) );
			return;
		}

		if ( _.isUndefined( state ) )
			state = ! this.enabled;

		this.enabled = state;

		cs.options.reply( 'skeleton:mode', this.enabled );

		if ( ! silent )
			cs.events.trigger( 'toggle:skeleton:mode', this.enabled );

		Backbone.$( 'body' ).toggleClass( 'cs-skeleton-active', this.enabled ).toggleClass( 'cs-skeleton-inactive', ! this.enabled );

		// if ( this.enabled ) {
		// 	cs.events.trigger( 'resize:skeleton' );
		// } else {
		// 	Backbone.$('.cs-preview').removeAttr('style');
		// }



	}

} );

},{}],24:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function() {

		cs.templates = Backbone.Radio.channel( 'cs:templates' );

		this.templates = new Cornerstone.Collections.Template();
		this.selections = new Backbone.Collection();

		this.listenToOnce(  cs.global, 'preview:primed', this.requestTemplates );
		this.on( 'templates:recieved', this.loadTemplates );

		this.listenTo( cs.templates, 'import', this.importTemplate );
		this.listenTo( cs.templates, 'save', this.save );
		this.listenTo( cs.templates, 'delete', this.deletion );
		this.listenTo( cs.templates, 'download', this.blockDownload );

	},

	requestTemplates: function() {
		cs.ajax( 'cs_templates', {}, {
			success: _.bind( function( response, options ) {
				this.templates.add( response );
				cs.templates.trigger( 'ready', this );
			}, this )
		} );
	},

	importTemplate: function( sections, format ) {

		// Convert slug to elements
		if ( 'string' == typeof sections ) {
			var template = this.templates.findWhere( { slug: sections } );
			sections = ( template ) ? template.get( 'elements' ) : [];
		}

		var elements = cs.post.elements();

		if ( 'page' == format )
			elements.reset();

		if ( ! sections || ! sections.length || 0 == sections.length ) {
			cs.message.trigger( 'error', cs.l18n( 'templates-error-import' ) );
			return;
		}

		// Example conversion if the format were to ever change
		if ( 'row' == sections[0].elType ) {
			sections = this.convertLegacy( _.clone( sections ) );
		}

		cs.ajax( 'cs_template_migration', { elements: sections }, {

			success: _.bind( function( response ) {

				var count = 0, inserts = [];

				cs.render.trigger( 'hold', true );

				var p = new Promise( function( resolve, reject ) {

					inserts = _.map( response.elements, function( section ) {
						return function() {

							try {
								//console.log( 'section added', count++ );
								elements.create( section );
							} catch ( e ) {
								console.warn( 'Template import error', e );
								reject();
								return;
							}

							_.defer( loop );

						};
					}, this );

					inserts.reverse();

					function loop() {
						if ( inserts.length <= 0 ) resolve();
						var cb = inserts.pop();
						if ( _.isFunction( cb ) ) cb();
					}

					loop();

				} ).then( function() {
					cs.render.trigger( 'hold', false );
					cs.message.trigger( 'success', ( 'page' == format  ) ? cs.l18n( 'templates-page-updated' ) : cs.l18n( 'templates-block-inserted' ) );
				}, fail );

			}, this ),
			error: fail,
			always: function( response ) {
				cs.log( 'template_migration', response );
			}
		} );

		function fail() {
			cs.render.trigger( 'hold', false );
			cs.message.trigger( 'error', cs.l18n( 'templates-error-import' ) );
		}

	},

	convertLegacy: function( sections ) {

		var moved = [ 'bg_type', 'bg_color', 'bg_image', 'bg_pattern_toggle', 'parallax', 'bg_video', 'bg_video_poster', 'margin', 'padding', 'border_style', 'border_color', 'visibility', 'class', 'style' ];

		return _.map( sections, function( section ) {

			var newSection = _.pick( section, moved );
			newSection.elType = 'section';
			newSection.elements = [];
			newSection.elements.push( _.omit( section, moved ) );

			return newSection;

		} );

  },

	save: function( type, title ) {

		var data = _.extend( _.pick( cs.post.toJSON(), [ 'elements' ] ), {
			type: type || 'block',
			title: title || 'Untitled'
		} );

		cs.ajax( 'cs_save_template', data, {
			success: _.bind( function( response ) {
				this.templates.add( response.template );
				cs.templates.trigger( 'add', this );
				cs.message.trigger( 'success', cs.l18n( 'templates-saved' ), 4000 );
			}, this ),
			error: function( response ) {
				cs.message.trigger( 'error', cs.l18n( 'templates-error-save' ), 10000 );
			},
			always: function( response ) {
				cs.log( 'save_template', response );
			}
		} );
	},

	deletion: function( slug ) {

		var model = this.templates.findWhere( { slug: slug } );
		if ( ! model ) return;

		model.destroy();
		this.deleteRemote( slug );
		cs.templates.trigger( 'remove', this );

	},

	deleteRemote: function( slug ) {
		cs.ajax( 'cs_delete_template', {
			slug: slug
		}, {
			success: function( response ) {
				cs.message.trigger( 'success', cs.l18n( 'templates-delete-success' ), 4000 );
			},
			error: function( response ) {
				cs.message.trigger( 'error', cs.l18n( 'templates-error-delete' ), 10000 );
			},
			always: function( response ) {
				cs.log( 'delete_template', response );
			}
		} );
	},

	blockDownload: function( name ) {

		try {
			!! new Blob;
		} catch ( e ) {
			cs.message.trigger( 'error', cs.l18n( 'browser-no-can' ) );
			return;
		}

		var name = name || 'template';

		var data = {
			title: name,
			elements: cs.post.toJSON().elements
		};

		var filename = name.replace( /\s+/g, '_' );

		var jsonData = JSON.stringify( data, null, 2 );
		Cornerstone.Vendor.FileSaver.saveAs( new Blob([jsonData], { type: 'text/plain;charset=utf-8' }), filename + '.csl' );

	}

} );

},{}],25:[function(require,module,exports){
module.exports = {

	// Common
	'builder':         require( './common/builder' ),
	'mn-extensions':   require( './common/mn-extensions' ),
	'template-loader': require( './common/template-loader' ),
	'view-loader':     require( './common/view-loader' ),
	'model-loader':    require( './common/model-loader' ),
	'render-queue':    require( './common/render-queue' ),
	'keybindings':     require( './common/keybindings' ),

	// Editor
	'editor':           require( './editor/editor' ),
	'element-pane':     require( './editor/element-library' ),
	'navigator':        require( './editor/navigator' ),
	'options':          require( './editor/options' ),
	'layout':           require( './editor/layout' ),
	'layout-templates': require( './editor/layout-templates' ),
	'inspector':        require( './editor/inspector' ),
	'post-handler':     require( './editor/post-handler' ),
	'settings':         require( './editor/settings' ),
	'template-manager': require( './editor/template-manager' ),
	'skeleton':         require( './editor/skeleton' ),
	'element-manager':  require( './editor/element-manager' ),
	'cheatsheet':       require( './editor/cheatsheet' ),

	// Preview
	'preview': require( './preview/preview' )

};

},{"./common/builder":5,"./common/keybindings":6,"./common/mn-extensions":7,"./common/model-loader":8,"./common/render-queue":9,"./common/template-loader":10,"./common/view-loader":11,"./editor/cheatsheet":12,"./editor/editor":13,"./editor/element-library":14,"./editor/element-manager":15,"./editor/inspector":16,"./editor/layout":18,"./editor/layout-templates":17,"./editor/navigator":19,"./editor/options":20,"./editor/post-handler":21,"./editor/settings":22,"./editor/skeleton":23,"./editor/template-manager":24,"./preview/preview":26}],26:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( options ) {

		Backbone.$( document ).ready(function() {
			Backbone.$( '#cs-content' ).empty();
		});

		cs.preview  = Backbone.Radio.channel( 'cs:preview' );
		cs.observer = Backbone.Radio.channel( 'cs:observer' );

		xData.isPreview = true;

		// Hook before preview initializes
    this.listenTo( cs.preview, 'iframe:ready', this.reload );

    // Hook after initialization. This does not gaurantee preview window is
    // accessible. You should probably use 'preview:reloaded' instead.
		cs.events.trigger( 'preview:init' );

		cs.preview.reply( 'responsive:text', null );

		this.listenTo( cs.preview, 'responsive:text', this.responsiveTextUpdate );
		this.listenTo( cs.preview, 'late:script:enqueue', this.lateScriptEnqueue );
		this.listenTo( cs.preview, 'late:style:enqueue', this.lateStyleEnqueue );

		// Fix: Uncaught ReferenceError: ajaxurl is not defined
		if ( cs.config( 'useLegacyAjax' ) ) {
			window.ajaxurl = cs.config( 'fallbackAjaxUrl' );
		}

	},

	reload: function( editor ) {

    cs.global = editor.cs.global;

    editor.cs.render = cs.render;

    this.cs.post = editor.cs.post;

    if ( 0 == Backbone.$( '#cs-content' ).length ) {
			console.log( 'Unable to initialize preview. #cs-content missing.' );
			this.noContentArea = true;
    }

    var PreviewView = require( '../../views/main/preview.js' );
    this.view = new PreviewView( { el: '#cs-content', model: this.cs.post.data } );
		this.view.render();

		this.sendWindowDimensions();
		this.listenToOnce( cs.render, 'primed', this.primed );

		_.delay( _.bind( function() {
			this.primed( true );
			this.sendWindowDimensions();
		}, this ), 9000 );

    this.listenTo( cs.global, 'settings:ready', this.settingsPingback );
    this.listenTo( cs.global, 'update:custom_css', this.customCSS );
    this.listenTo( cs.global, 'update:responsive_text', this.responsiveText );

    cs.events.trigger( 'preview:iframe:reloaded' );

    cs.observer.reply( 'get:collapse', false );
    this.listenTo( cs.global, 'set:collapse', function( state ) {
			cs.observer.reply( 'get:collapse', state );
		} );

		Backbone.$( window ).resize( function() {
			cs.events.trigger( 'preview:resize' );
		} );

		this.listenTo( cs.events, 'preview:resize', this.sendWindowDimensions );

  },

  sendWindowDimensions: function() {
		var $body = Backbone.$( 'body' );
		var $window = Backbone.$( window );
		cs.global.trigger( 'preview:resize', {
			Window: {
				width: $window.width(),
				height: $window.height()
			},
			Body: {
				width: $body.width(),
				height: $body.height()
			}

		} );
  },

  primed: function( late ) {

		if ( this.isPrimed || cs.global.request( 'editor:ready' ) )
			return;

		this.isPrimed = true;
		cs.global.trigger( 'preview:primed', late || false );

		if ( this.noContentArea ) {
			_.defer( function() {
				cs.global.trigger( 'preview:failure', cs.l18n( 'preview-failure2' ) );
			});
		}

	},

	settingsPingback: function() {
		cs.global.trigger( 'settings:pingback' );
	},

	customCSS: function( value ) {
		Backbone.$( '#cornerstone-custom-page-css' ).html( value );
	},

	responsiveText: function( elements ) {
		cs.preview.reply( 'responsive:text', elements );
		Backbone.$( window ).trigger( 'fittextReset' );
		cs.preview.trigger( 'responsive:text' );
	},

	responsiveTextUpdate: function( scope ) {

		var scope = scope || Backbone;

		var items = cs.preview.request( 'responsive:text' );

		if ( ! items ) return;

		_.each( items, function( item ) {

			var $items = scope.$( item.selector );

				_.defer( function() {
					$items.css( 'font-size', '' );
					$items.csFitText( item.compression, {
						minFontSize: item.min_size,
						maxFontSize: item.max_size
					});
				} );

		}, this );

	},

  lateScriptEnqueue: function( handle, script, done ) {

		if ( 0 == Backbone.$( 'script[data-cs-handle="' + handle + '"]' ).length && script.src ) {

			var timeout = setTimeout( function() {
				cs.warn(  'Unable to load: ' + handle );
				done();
			}, 10000 );

			if ( script.before ) {
				var $before = Backbone.$( '<script data-cs-script-extra="' + handle + '">' + script.before + '</script>' );
				Backbone.$( 'body' ).append( $before );
			}

			var scriptEl = document.createElement( 'script' );
			Backbone.$( 'body' ).append( scriptEl );
			scriptEl.onload = function() {
				clearTimeout( timeout );
				cs.log( 'Cornerstone | Runtime script loaded: ' + handle );
				_.defer( done );
			};

			scriptEl.src = script.src;

			Backbone.$( scriptEl ).attr( 'data-cs-handle', handle );

		} else {
			done();
		}

  },

  lateStyleEnqueue: function( handle, style, done ) {

		if ( 0 == Backbone.$( 'script[data-cs-handle="' + handle + '"]' ).length && style.tag ) {

			var timeout = setTimeout( function() {
				cs.warn(  'Unable to load: ' + handle );
				done();
			}, 10000 );

			var $styleEl = Backbone.$( style.tag );
			$styleEl[0].onload = function() {
				clearTimeout( timeout );
				cs.log( 'Cornerstone | Runtime style loaded: ' + handle );
				_.defer( done );
			};

			Backbone.$( 'head' ).append( $styleEl );
			done();

		} else {
			done();
		}

  }

} );

},{"../../views/main/preview.js":158}],27:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/base 
__p+=' ';
 if ( ui.title ) { 
__p+=' <div class="cs-control-header"> <label ';
 if ( ui.tooltip ) { 
__p+=' data-tooltip-message="'+
((__t=( ui.tooltip ))==null?'':__t)+
'" ';
 } 
__p+='>'+
((__t=( ui.title ))==null?'':__t)+
'</label> </div> ';
 } 
__p+=' <input type="hidden" value=""> '+
((__t=( render( controlTemplate, arguments[0] ) ))==null?'':__t)+
' ';
 if ( ui.message ) { 
__p+=' <div class="cs-control-footer"> <span>'+
((__t=( ui.message ))==null?'':__t)+
'</span> </div> ';
 } 
__p+='';
}
return __p;
};

},{}],28:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/choose 
__p+=' <ul class="cs-choose cols-'+
((__t=( options.columns ))==null?'':__t)+
'"> ';
 _.each( options.choices, function(item) { 
__p+=' <li data-choice="'+
((__t=( item.value ))==null?'':__t)+
'"> <i class="cs-icon" data-cs-icon="'+
((__t=( item.icon ))==null?'':__t)+
'" ';
 if (item.tooltip) { 
__p+=' title="'+
((__t=( item.tooltip ))==null?'':__t)+
'" ';
 } 
__p+='></i> ';
 if (item.label) { 
__p+='<span>'+
((__t=( item.label ))==null?'':__t)+
'</span>';
 } 
__p+=' </li> ';
 }); 
__p+=' </ul>';
}
return __p;
};

},{}],29:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/color 
__p+=' <input type="text" class="cs-color-input">';
}
return __p;
};

},{}],30:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/column-layout 
__p+=' <ul class="cs-column-layout"> <li class="prefab" data-layout="1/1"><span class="cs-1-1">1/1</span></li> <li class="prefab" data-layout="1/2 + 1/2"><span class="cs-1-2">1/2</span><span class="cs-1-2">1/2</span></li> <li class="prefab" data-layout="1/3 + 2/3"><span class="cs-1-3">1/3</span><span class="cs-2-3">2/3</span></li> <li class="prefab" data-layout="2/3 + 1/3"><span class="cs-2-3">2/3</span><span class="cs-1-3">1/3</span></li> <li class="prefab" data-layout="1/3 + 1/3 + 1/3"><span class="cs-1-3">1/3</span><span class="cs-1-3">1/3</span><span class="cs-1-3">1/3</span></li> <li class="prefab" data-layout="1/4 + 1/4 + 1/4 + 1/4"><span class="cs-1-4">1/4</span><span class="cs-1-4">1/4</span><span class="cs-1-4">1/4</span><span class="cs-1-4">1/4</span></li> <li class="prefab" data-layout="1/5 + 1/5 + 1/5 + 1/5 + 1/5"><span class="cs-1-5">1/5</span><span class="cs-1-5">1/5</span><span class="cs-1-5">1/5</span><span class="cs-1-5">1/5</span><span class="cs-1-5">1/5</span></li> <li class="custom"><span class="cs-1-1 custom"><span>'+
((__t=( cs.l18n('columns-layout-custom') ))==null?'':__t)+
'</span></span></li> </ul> <input type="text" id="column-layout" value="'+
((__t=( _column_layout ))==null?'':__t)+
'">';
}
return __p;
};

},{}],31:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/column-layout 
__p+=' <span class="handle"><span>'+
((__t=( size ))==null?'':__t)+
'</span></span>';
}
return __p;
};

},{}],32:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/column-order 
__p+=' <ul class="cs-column-order"></ul>';
}
return __p;
};

},{}],33:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+=''+
((__t=( message ))==null?'':__t)+
'';
}
return __p;
};

},{}],34:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<input type="text" class="cs-date-input"> <div class="cs-date-container"> <div class="cs-date-picker-entry"></div> <div class="cs-date-format"> <div class="cs-date-format-label">Select a Format<select tabindex="-1"></select></div> </div> </div>';
}
return __p;
};

},{}],35:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/default 
__p+=' <span>'+
((__t=( debug('Control <strong>' + controlType + '</strong> could not be found.') ))==null?'':__t)+
'</span>';
}
return __p;
};

},{}],36:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/dimensions 
__p+=' <ul class="cs-dimensions"> <li><input data-edge="top" type="text"><span>'+
((__t=( l18n('dimensions-top') ))==null?'':__t)+
'</span></li> <li><input data-edge="right" type="text"><span>'+
((__t=( l18n('dimensions-right') ))==null?'':__t)+
'</span></li> <li><input data-edge="bottom" type="text"><span>'+
((__t=( l18n('dimensions-bottom') ))==null?'':__t)+
'</span></li> <li><input data-edge="left" type="text"><span>'+
((__t=( l18n('dimensions-left') ))==null?'':__t)+
'</span></li> <li> <button class="cs-link-dimensions"> <i class="cs-icon link" data-cs-icon="&#xf0c1;" title="'+
((__t=( l18n('dimensions-unlink') ))==null?'':__t)+
'"></i> <i class="cs-icon unlink" data-cs-icon="&#xf127;" title="'+
((__t=( l18n('dimensions-link') ))==null?'':__t)+
'"></i> </button> </li> </ul>';
}
return __p;
};

},{}],37:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/expand-control-button 
__p+=' <button class="cs-expand-control"> <span class="dashicons dashicons-editor-expand"></span>'+
((__t=( cs.l18n('expand-control') ))==null?'':__t)+
' </button>';
}
return __p;
};

},{}],38:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<li title="'+
((__t=( choice ))==null?'':__t)+
'" data-choice="'+
((__t=( choice ))==null?'':__t)+
'" data-choices="'+
((__t=( choices ))==null?'':__t)+
'"><i class="cs-icon" data-cs-icon="&#x'+
((__t=( code ))==null?'':__t)+
'"></i></li>';
}
return __p;
};

},{}],39:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/icon-choose 
__p+=' <div class="cs-icons-outer';
 if (options.expandable) { print(' cs-expandable') } 
__p+='"> <div class="cs-search-section"> <div class="cs-search"> <input class="cs-search-input" type="search" placeholder="Search Icons"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('search')))==null?'':__t)+
'"></i> </div> </div> <div class="cs-icons-inner"> <ul class="cs-choose single"></ul> </div> ';
 if (options.expandable) { print(render( 'controls/expand-control-button' )) } 
__p+=' </div>';
}
return __p;
};

},{}],40:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/image 
__p+=' <div class="cs-image"> <i class="cs-icon add" data-cs-icon="'+
((__t=( fontIcon('plus-circle') ))==null?'':__t)+
'"></i> <i class="cs-icon remove" data-cs-icon="'+
((__t=( fontIcon('times-circle') ))==null?'':__t)+
'"></i> </div>';
}
return __p;
};

},{}],41:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if ( ui.title ) { 
__p+='<h4>'+
((__t=( ui.title ))==null?'':__t)+
'</h4>';
 } 
__p+=' ';
 if ( ui.message ) { 
__p+='<p>'+
((__t=( ui.message ))==null?'':__t)+
'</p>';
 } 
__p+='';
}
return __p;
};

},{}],42:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/number 
__p+=' <div> <input type="number" value=""> ';
 if (options.units) { 
__p+=' <span>'+
((__t=( options.units ))==null?'':__t)+
'</span> ';
 } 
__p+=' </div>';
}
return __p;
};

},{}],43:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/select 
__p+=' <select> ';
 _.each( options.choices, function(item) { 
__p+=' <option value="'+
((__t=( item.value ))==null?'':__t)+
'">'+
((__t=( item.label ))==null?'':__t)+
'</option> ';
 }); 
__p+=' </select>';
}
return __p;
};

},{}],44:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //controls/sortable-empty 
__p+=' <span class="handle"><i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('plus-square') ))==null?'':__t)+
'"></i> <span>Add</span></span>';
}
return __p;
};

},{}],45:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //controls/sortable-item-ext 
__p+=' <span class="handle">'+
((__t=( title ))==null?'':__t)+
'</span> <div class="controls"> <button class="action1 cs-icon" data-cs-icon="'+
((__t=( fontIcon( actions[0].icon ) ))==null?'':__t)+
'" title="'+
((__t=( actions[0].tooltip ))==null?'':__t)+
'"></button> <button class="action2 cs-icon" data-cs-icon="'+
((__t=( fontIcon( actions[1].icon ) ))==null?'':__t)+
'" title="'+
((__t=( actions[1].tooltip ))==null?'':__t)+
'"></button> </div> <div class="controls extra"> <button class="action3 cs-icon" data-cs-icon="'+
((__t=( fontIcon( actions[2].icon ) ))==null?'':__t)+
'" title="'+
((__t=( actions[2].tooltip ))==null?'':__t)+
'"></button> </div>';
}
return __p;
};

},{}],46:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //controls/sortable-item 
__p+=' <span class="handle">'+
((__t=( title ))==null?'':__t)+
'</span> <div class="controls"> <button class="action1 cs-icon" data-cs-icon="'+
((__t=( fontIcon( actions[0].icon ) ))==null?'':__t)+
'" title="'+
((__t=( actions[0].tooltip ))==null?'':__t)+
'"></button> <button class="action2 cs-icon" data-cs-icon="'+
((__t=( fontIcon( actions[1].icon ) ))==null?'':__t)+
'" title="'+
((__t=( actions[1].tooltip ))==null?'':__t)+
'"></button> </div>';
}
return __p;
};

},{}],47:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-sortable"></ul> ';
 if (canAdd && !empty) { 
__p+=' <button class="cs-add-sortable-item"> <i class="cs-icon" data-cs-icon="&#xf0fe;"></i> <span>'+
((__t=( cs.l18n('sortable-add') ))==null?'':__t)+
'</span> </button> ';
 } 
__p+='';
}
return __p;
};

},{}],48:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/template-select 
__p+=' <select> ';
 _.each( options.choices, function(item) { 
__p+=' <option value="'+
((__t=( item.value ))==null?'':__t)+
'">'+
((__t=( item.label ))==null?'':__t)+
'</option> ';
 }); 
__p+=' </select> <button class="'+
((__t=( options.templateType ))==null?'':__t)+
'">'+
((__t=( ui.buttonText ))==null?'':__t)+
'</button>';
}
return __p;
};

},{}],49:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/text 
__p+=' <input '+
((__t=( (options.monospace) ? "style=\"font-family:monospace;\"" : "" ))==null?'':__t)+
' type="text" value="">';
}
return __p;
};

},{}],50:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/textarea 
__p+=' <textarea '+
((__t=( (options.monospace) ? "style=\"font-family:monospace;\"" : "" ))==null?'':__t)+
'></textarea> ';
 if (options.expandable) { print(render( 'controls/expand-control-button' )) } 
__p+='';
}
return __p;
};

},{}],51:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-title ';
 if (options.showInspectButton) { 
__p+='inspectable';
 } 
__p+='"> <input type="text" class="cs-title-input"> ';
 if (options.showInspectButton) { 
__p+=' <button class="cs-title-button" title="'+
((__t=( l18n('tooltip-inspect') ))==null?'':__t)+
'"><i class="cs-icon" data-cs-icon="&#xf002;"></i></button> ';
 } 
__p+=' </div>';
}
return __p;
};

},{}],52:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/toggle 
__p+=' <ul class="cs-toggle"> <li class="on"><span>'+
((__t=( l18n('controls-on') ))==null?'':__t)+
'</span></li> <li class="off"><span>'+
((__t=( l18n('controls-off') ))==null?'':__t)+
'</span></li> </ul>';
}
return __p;
};

},{}],53:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/controls/select 
__p+=' <div class="cs-wp-select"></div>';
}
return __p;
};

},{}],54:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-dragging-placeholder"> <svg class="cs-custom-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-290 382 30 30" enable-background="new -290 382 30 30" xml:space="preserve"> <g> <path d="M-275,395.9l12-6.4l-11.5-6c-0.3-0.2-0.6-0.2-0.9,0l-11.6,6.1L-275,395.9z"/> <path d="M-274,397.5v12.7l11.4-6.1c0.3-0.2,0.5-0.5,0.5-0.9v-12.1L-274,397.5z"/> <path d="M-276,397.5l-11.9-6.3v12.1c0,0.4,0.2,0.7,0.5,0.9l11.4,6V397.5z"/> </g> </svg> </div>';
}
return __p;
};

},{}],55:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-empty-column"> <svg class="cs-custom-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-290 382 30 30" enable-background="new -290 382 30 30" xml:space="preserve"> <g> <path d="M-275,395.9l12-6.4l-11.5-6c-0.3-0.2-0.6-0.2-0.9,0l-11.6,6.1L-275,395.9z"/> <path d="M-274,397.5v12.7l11.4-6.1c0.3-0.2,0.5-0.5,0.5-0.9v-12.1L-274,397.5z"/> <path d="M-276,397.5l-11.9-6.3v12.1c0,0.4,0.2,0.7,0.5,0.9l11.4,6V397.5z"/> </g> </svg> </div>';
}
return __p;
};

},{}],56:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-empty-element"> <div class="cs-empty-element-icon"> '+
((__t=( cs.elementIcon( _type ) ))==null?'':__t)+
' </div> </div>';
}
return __p;
};

},{}],57:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 // elements/empty-rows 
__p+=' '+
((__t=( cs.icon('interface/logo-flat-muted') ))==null?'':__t)+
' <h2>Welcome to Cornerstone</h2> <p>Get started by adding sections to the <strong class="cs-empty-rows-layout">'+
((__t=( cs.icon('interface/nav-layout-solid') ))==null?'':__t)+
'Layout</strong> pane in the sidebar or begin with a template. Click on your sections to add rows and alter column structure, then go to the <strong class="cs-empty-rows-elements">'+
((__t=( cs.icon('interface/nav-elements-solid') ))==null?'':__t)+
'Elements</strong> pane and begin dragging in your items. Clicking on any element in the preview area takes you to the <strong class="cs-empty-rows-inspector">'+
((__t=( cs.icon('interface/nav-inspector-solid') ))==null?'':__t)+
'Inspector</strong> pane to alter its appearance. Happy building!</p>';
}
return __p;
};

},{}],58:[function(require,module,exports){
module.exports = {

  'dragging-placeholder': require( './dragging-placeholder' ),
  'empty-column':         require( './empty-column' ),
  'empty-element':        require( './empty-element' ),
  'empty-rows':           require( './empty-rows' ),
  'loading':              require( './loading' ),
  'observer':             require( './observer' )

};

},{"./dragging-placeholder":54,"./empty-column":55,"./empty-element":56,"./empty-rows":57,"./loading":59,"./observer":60}],59:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 // elements/loading 
__p+=' <div class="cs-empty-element"> <div class="cs-empty-element-icon"> <i class="cs-icon cs-icon-loading" data-cs-icon="&#xf110;"></i> </div> </div>';
}
return __p;
};

},{}],60:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-observer-tooltip top left">'+
((__t=( tooltip ))==null?'':__t)+
'</div>';
}
return __p;
};

},{}],61:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/extra/confirm 
__p+=' <div class="'+
((__t=( contentClass ))==null?'':__t)+
'"> ';
 if ( message ) { 
__p+='<p class="message">'+
((__t=( message ))==null?'':__t)+
'</p>';
 } 
__p+=' ';
 if ( yep )  { 
__p+='<button class="action yep sad">'+
((__t=( yep ))==null?'':__t)+
'</button>';
 } 
__p+=' ';
 if ( nope ) { 
__p+='<button class="action nope">'+
((__t=( nope ))==null?'':__t)+
'</button>';
 } 
__p+=' ';
 if ( subtext ) { 
__p+='<p class="subtext">'+
((__t=( subtext ))==null?'':__t)+
'</p>';
 } 
__p+=' </div>';
}
return __p;
};

},{}],62:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-expanded-content-inner"></div> <button class="cs-expanded-close">&times;</button>';
}
return __p;
};

},{}],63:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/extra/home 
__p+=' <p class="saved-last '+
((__t=( savedLastClass ))==null?'':__t)+
'">'+
((__t=( savedLastMessage ))==null?'':__t)+
'</p> <ul class="cs-controls"> <li class="cs-control cs-control-actions"> <ul class="cs-actions"> <li class="action new"> <a href="'+
((__t=( dashboardEditUrl ))==null?'':__t)+
'"> <i class="cs-icon" data-cs-icon="&#xf19a;"></i> <span>'+
((__t=( l18n('home-dashboard') ))==null?'':__t)+
'</span> </a> </li> <li class="action view-site"> <a href="'+
((__t=( frontEndUrl ))==null?'':__t)+
'"> <i class="cs-icon" data-cs-icon="&#xf14c;"></i> <span>'+
((__t=( l18n('home-view-site') ))==null?'':__t)+
'</span> </a> </li> </ul> </li> </ul>';
}
return __p;
};

},{}],64:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/extra/options 
__p+='';
}
return __p;
};

},{}],65:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/extra/respond 
__p+=' <div class="cs-respond-buttons"> <button class="cs-icon xl" data-respond="xl" data-cs-icon="'+
((__t=( cs.fontIcon('desktop') ))==null?'':__t)+
'"></button> <button class="cs-icon lg" data-respond="lg" data-cs-icon="'+
((__t=( cs.fontIcon('laptop') ))==null?'':__t)+
'"></button> <button class="cs-icon md" data-respond="md" data-cs-icon="'+
((__t=( cs.fontIcon('tablet') ))==null?'':__t)+
'"></button> <button class="cs-icon sm" data-respond="sm" data-cs-icon="'+
((__t=( cs.fontIcon('tablet') ))==null?'':__t)+
'"></button> <button class="cs-icon xs" data-respond="xs" data-cs-icon="'+
((__t=( cs.fontIcon('mobile') ))==null?'':__t)+
'"></button> </div> <div class="cs-respond-labels"> <div class="xl" data-respond="xl"><i class="cs-icon" data-cs-icon="'+
((__t=( cs.fontIcon('desktop') ))==null?'':__t)+
'"></i><span class="label">'+
((__t=( l18n('respond-xl-title') ))==null?'':__t)+
'</span><span class="size">'+
((__t=( l18n('respond-xl-desc') ))==null?'':__t)+
'</span></div> <div class="lg" data-respond="lg"><i class="cs-icon" data-cs-icon="'+
((__t=( cs.fontIcon('laptop')  ))==null?'':__t)+
'"></i><span class="label">'+
((__t=( l18n('respond-lg-title') ))==null?'':__t)+
'</span><span class="size">'+
((__t=( l18n('respond-lg-desc') ))==null?'':__t)+
'</span></div> <div class="md" data-respond="md"><i class="cs-icon" data-cs-icon="'+
((__t=( cs.fontIcon('tablet')  ))==null?'':__t)+
'"></i><span class="label">'+
((__t=( l18n('respond-md-title') ))==null?'':__t)+
'</span><span class="size">'+
((__t=( l18n('respond-md-desc') ))==null?'':__t)+
'</span></div> <div class="sm" data-respond="sm"><i class="cs-icon" data-cs-icon="'+
((__t=( cs.fontIcon('tablet')  ))==null?'':__t)+
'"></i><span class="label">'+
((__t=( l18n('respond-sm-title') ))==null?'':__t)+
'</span><span class="size">'+
((__t=( l18n('respond-sm-desc') ))==null?'':__t)+
'</span></div> <div class="xs" data-respond="xs"><i class="cs-icon" data-cs-icon="'+
((__t=( cs.fontIcon('mobile')  ))==null?'':__t)+
'"></i><span class="label">'+
((__t=( l18n('respond-xs-title') ))==null?'':__t)+
'</span><span class="size">'+
((__t=( l18n('respond-xs-desc') ))==null?'':__t)+
'</span></div> </div>';
}
return __p;
};

},{}],66:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/extra/save-complete 
__p+=' <p class="message">'+
((__t=( message ))==null?'':__t)+
'</p>';
}
return __p;
};

},{}],67:[function(require,module,exports){
module.exports = {

  'controls/base':                  require( './controls/base'),
  'controls/choose':                require( './controls/choose'),
  'controls/color':                 require( './controls/color'),
  'controls/column-layout':         require( './controls/column-layout'),
  'controls/column-order-item':     require( './controls/column-order-item'),
  'controls/column-order':          require( './controls/column-order'),
  'controls/custom-markup':         require( './controls/custom-markup'),
  'controls/date':                  require( './controls/date'),
  'controls/default':               require( './controls/default'),
  'controls/dimensions':            require( './controls/dimensions'),
  'controls/expand-control-button': require( './controls/expand-control-button'),
  'controls/icon-choose':           require( './controls/icon-choose'),
  'controls/icon-choose-item':      require( './controls/icon-choose-item'),
  'controls/image':                 require( './controls/image'),
  'controls/info-box':              require( './controls/info-box'),
  'controls/number':                require( './controls/number'),
  'controls/select':                require( './controls/select'),
  'controls/sortable-empty':        require( './controls/sortable-empty'),
  'controls/sortable-item-wide':    require( './controls/sortable-item-wide'),
  'controls/sortable-item':         require( './controls/sortable-item'),
  'controls/sortable':              require( './controls/sortable'),
  'controls/template-select':       require( './controls/template-select'),
  'controls/textarea':              require( './controls/textarea'),
  'controls/text':                  require( './controls/text'),
  'controls/title':                 require( './controls/title'),
  'controls/toggle':                require( './controls/toggle'),
  'controls/wpselect':              require( './controls/wpselect'),

  'extra/confirm':          require( './extra/confirm'),
  'extra/expanded-control': require( './extra/expanded-control'),
  'extra/home':             require( './extra/home'),
  'extra/options':          require( './extra/options'),
  'extra/respond':          require( './extra/respond'),
  'extra/save-complete':    require( './extra/save-complete'),

  'inspector/blank-state':     require( './inspector/blank-state'),
  'inspector/breadcrumbs':     require( './inspector/breadcrumbs'),
  'inspector/column-actions':  require( './inspector/column-actions'),
  'inspector/element-actions': require( './inspector/element-actions'),
  'inspector/row-actions':     require( './inspector/row-actions'),

  'layout/sub-row/layout-sub-row':             require( './layout/sub-row/layout-sub-row'),
  'layout/sub-templates/layout-sub-template': require( './layout/sub-templates/layout-sub-template'),
  'layout/sub-templates/save-dialog':          require( './layout/sub-templates/save-dialog'),
  'layout/sub-templates/template-actions':     require( './layout/sub-templates/template-actions'),
  'layout/sub-templates/upload-dialog':        require( './layout/sub-templates/upload-dialog'),
  'layout/actions':                            require( './layout/actions'),

  'library/element-stub': require( './library/element-stub'),
  'library/search':       require( './library/search'),

  'main/editor':        require( './main/editor'),
  'main/extra':         require( './main/extra'),
  'main/footer':        require( './main/footer'),
  'main/header':        require( './main/header'),
  'main/pane':          require( './main/pane'),
  'main/skeleton-item': require( './main/skeleton-item'),
  'main/skeleton':      require( './main/skeleton'),

  'settings/actions': require( './settings/actions'),
  'settings/content': require( './settings/content'),
  'settings/section': require( './settings/section'),

  'utility/htmlhint': require( './utility/htmlhint')

};

},{"./controls/base":27,"./controls/choose":28,"./controls/color":29,"./controls/column-layout":30,"./controls/column-order":32,"./controls/column-order-item":31,"./controls/custom-markup":33,"./controls/date":34,"./controls/default":35,"./controls/dimensions":36,"./controls/expand-control-button":37,"./controls/icon-choose":39,"./controls/icon-choose-item":38,"./controls/image":40,"./controls/info-box":41,"./controls/number":42,"./controls/select":43,"./controls/sortable":47,"./controls/sortable-empty":44,"./controls/sortable-item":46,"./controls/sortable-item-wide":45,"./controls/template-select":48,"./controls/text":49,"./controls/textarea":50,"./controls/title":51,"./controls/toggle":52,"./controls/wpselect":53,"./extra/confirm":61,"./extra/expanded-control":62,"./extra/home":63,"./extra/options":64,"./extra/respond":65,"./extra/save-complete":66,"./inspector/blank-state":68,"./inspector/breadcrumbs":69,"./inspector/column-actions":70,"./inspector/element-actions":71,"./inspector/row-actions":72,"./layout/actions":73,"./layout/sub-row/layout-sub-row":74,"./layout/sub-templates/layout-sub-template":75,"./layout/sub-templates/save-dialog":76,"./layout/sub-templates/template-actions":77,"./layout/sub-templates/upload-dialog":78,"./library/element-stub":79,"./library/search":80,"./main/editor":81,"./main/extra":82,"./main/footer":83,"./main/header":84,"./main/pane":85,"./main/skeleton":87,"./main/skeleton-item":86,"./settings/actions":88,"./settings/content":89,"./settings/section":90,"./utility/htmlhint":91}],68:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/inspector/blank-state 
__p+=' '+
((__t=( cs.icon('interface/logo-flat-inspector') ))==null?'':__t)+
' <span class="title">'+
((__t=( l18n('inspector-blank-pane-title') ))==null?'':__t)+
'</span> <span>'+
((__t=( l18n('inspector-blank-pane-message') ))==null?'':__t)+
'</span>';
}
return __p;
};

},{}],69:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if ( count > 0 ) { 
__p+='<button data-level="0" ';
 if ( items.length == 1 ) { print('class="disabled"') } 
__p+='>';
 print((items.length == 1) ? _.first( items ).title : _.first( items ).label ) 
__p+='</button>';
 _.each( _.rest( items ), function(item,index) { 
__p+='<span><i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon( (rtl) ? 'angle-left' : 'angle-right' ) ))==null?'':__t)+
'"></i></span><button ';
 if ( count == index+2 ) { print('class="disabled"') } 
__p+=' data-level="'+
((__t=( index + 1 ))==null?'':__t)+
'">'+
((__t=( item.label ))==null?'':__t)+
'</button>';
 }) 
__p+='';
 } 
__p+='';
}
return __p;
};

},{}],70:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action manage-layout"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('bars') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-manage-layout') ))==null?'':__t)+
'</span> </li> <li class="action erase"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('eraser') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-erase') ))==null?'':__t)+
'</span> <span class="quick-confirm">'+
((__t=( l18n('inspector-really-erase') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],71:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action duplicate"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('copy') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-duplicate') ))==null?'':__t)+
'</span> </li> <li class="action delete"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('trash-o') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-delete') ))==null?'':__t)+
'</span> <span class="quick-confirm">'+
((__t=( l18n('inspector-really-delete') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],72:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action manage-layout"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('bars') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-manage-layout') ))==null?'':__t)+
'</span> </li> <li class="action delete"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('trash-o') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('inspector-delete') ))==null?'':__t)+
'</span> <span class="quick-confirm">'+
((__t=( l18n('inspector-really-delete') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],73:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action new"> <i class="cs-icon" data-cs-icon="&#xf0fe;"></i> <span>'+
((__t=( l18n('layout-add-section') ))==null?'':__t)+
'</span> </li> <li class="action templates"> <i class="cs-icon" data-cs-icon="&#xf15b;"></i> <span>'+
((__t=( l18n('layout-templates') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],74:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/layout/sub-row/layout-sub-row 
__p+=' <div id="layout-row-controls" class="cs-pane-section controls"></div> <div id="layout-column-controls" class="cs-pane-section controls"></div>';
}
return __p;
};

},{}],75:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/layout/sub-templates/layout-sub-templates 
__p+=' <div id="layout-template-controls" class="cs-pane-section controls"></div> <div id="layout-template-sections" class="cs-pane-section sections"></div>';
}
return __p;
};

},{}],76:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-title"> <input type="text" class="cs-title-input" value="'+
((__t=( title ))==null?'':__t)+
'"> </div> <ul class="cs-actions"> <li class="action download"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('download') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('templates-download') ))==null?'':__t)+
'</span> </li> <li class="action save"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('book') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('templates-save-library') ))==null?'':__t)+
'</span> </li> </ul> <button class="close">&times;</button>';
}
return __p;
};

},{}],77:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action save"> <i class="cs-icon" data-cs-icon="&#xf0c7;"></i> <span>'+
((__t=( l18n('templates-save') ))==null?'':__t)+
'</span> </li> <li class="action upload"> <i class="cs-icon" data-cs-icon="&#xf093;"></i> <span>'+
((__t=( l18n('templates-upload') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],78:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<input id="template-upload" type="file" name="blockUpload"> <button class="process">'+
((__t=( l18n('templates-upload-button') ))==null?'':__t)+
'</button> <button class="close">&times;</button>';
}
return __p;
};

},{}],79:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/library/element-stub 
__p+=' <span class="icon">'+
((__t=( icon ))==null?'':__t)+
'</span> <span class="name"><span>'+
((__t=( ui.title ))==null?'':__t)+
'</span></span>';
}
return __p;
};

},{}],80:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-search-section"> <div class="cs-search"> <input type="search" placeholder="'+
((__t=( l18n('elements-search') ))==null?'':__t)+
'" id="elements-search"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('search') ))==null?'':__t)+
'"></i> </div> </div>';
}
return __p;
};

},{}],81:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/main/editor 
__p+=' <header id="header" class="cs-editor-header"></header> <section id="pane"></section> <div id="skeleton" class="cs-editor-skeleton"></div> <footer id="footer" class="cs-editor-footer"></footer> <div id="expand" class="cs-editor-expansion"></div>';
}
return __p;
};

},{}],82:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-editor-extra"> <div class="cs-tooltip-outer"><div class="cs-tooltip-inner"></div></div> </div>';
}
return __p;
};

},{}],83:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/main/footer 
__p+=' <nav> <button class="collapse cs-icon" data-cs-icon="'+
((__t=( fontIcon('play-circle') ))==null?'':__t)+
'"></button> <button class="home cs-icon has-flyout" data-cs-icon="'+
((__t=( fontIcon('home') ))==null?'':__t)+
'"></button> <button class="help-text cs-icon" data-toggle="help:text" data-cs-icon="'+
((__t=( fontIcon('info-circle') ))==null?'':__t)+
'"></button> <button class="skeleton-mode cs-icon" data-toggle="skeleton:mode" data-cs-icon="'+
((__t=( fontIcon('object-group') ))==null?'':__t)+
'"></button> <button class="respond cs-icon has-flyout" data-cs-icon="'+
((__t=( fontIcon('mobile') ))==null?'':__t)+
'"></button> <button class="save">'+
((__t=( l18n('footer-button-save') ))==null?'':__t)+
'</button> </nav> <div class="cs-editor-extra"> <div class="cs-tooltip-outer"><div class="cs-tooltip-inner"></div></div> </div>';
}
return __p;
};

},{}],84:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/main/header 
__p+=' <button class="layout">'+
((__t=( cs.icon('interface/nav-layout-solid') ))==null?'':__t)+
'</button> <button class="elements">'+
((__t=( cs.icon('interface/nav-elements-solid') ))==null?'':__t)+
'</button> <button class="inspector">'+
((__t=( cs.icon('interface/nav-inspector-solid') ))==null?'':__t)+
'</button> <button class="settings">'+
((__t=( cs.icon('interface/nav-settings-solid') ))==null?'':__t)+
'</button>';
}
return __p;
};

},{}],85:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<h2>'+
((__t=( heading ))==null?'':__t)+
'</h2> <div class="cs-pane-content-outer"> ';
 if ( paneTemplate ) { 
__p+=' '+
((__t=( render( paneTemplate, arguments[0] ) ))==null?'':__t)+
' ';
 } 
__p+=' <div id="content" class="cs-pane-content-inner" style="right:0px"></div> </div> <div class="cs-builder-sub '+
((__t=( name ))==null?'':__t)+
'"> <button class="cs-builder-sub-back"> <i class="cs-icon" data-cs-icon="&#xf053;"></i> <span>'+
((__t=( returnButtonText ))==null?'':__t)+
'</span> </button> <div class="cs-pane-content-outer"> <div id="sub" class="cs-pane-content-inner"></div> </div> </div>';
}
return __p;
};

},{}],86:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-skeleton-handle"> <div class="cs-skeleton-title">'+
((__t=( title ))==null?'':__t)+
'</div> <div class="cs-skeleton-controls"> ';
 _.each( controls, function( item ) {
			var buttonClass = item.action + ( ( item.persist ) ? ' persist' : '');
		
__p+=' <button class="'+
((__t=( buttonClass ))==null?'':__t)+
' cs-icon" data-action="'+
((__t=( item.action ))==null?'':__t)+
'" data-cs-icon="'+
((__t=( fontIcon( item.icon ) ))==null?'':__t)+
'" ';
 if ( item.iconAlt ) { 
__p+=' data-cs-icon-alt="'+
((__t=( fontIcon( item.iconAlt ) ))==null?'':__t)+
'" ';
 } 
__p+='></button> ';
 }); 
__p+=' </div> </div> ';
 if ( _type == 'row' ) { 
__p+=' <div class="cs-skeleton-container-outer"> <div class="cs-skeleton-container-inner cs-skeleton-container-'+
((__t=( _type ))==null?'':__t)+
'"></div> </div> ';
 } else { 
__p+=' <div class="cs-skeleton-container cs-skeleton-container-'+
((__t=( _type ))==null?'':__t)+
'"></div> ';
 } 
__p+='';
}
return __p;
};

},{}],87:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="cs-skeleton-content-inner"> <div class="cs-skeleton-items"></div> </div>';
}
return __p;
};

},{}],88:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<ul class="cs-actions"> <li class="action css"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('paint-brush') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('settings-css-editor') ))==null?'':__t)+
'</span> </li> <li class="action js"> <i class="cs-icon" data-cs-icon="'+
((__t=( fontIcon('code') ))==null?'':__t)+
'"></i> <span>'+
((__t=( l18n('settings-js-editor') ))==null?'':__t)+
'</span> </li> </ul>';
}
return __p;
};

},{}],89:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 //builder/settings/page-settings 
__p+=' <div id="controls"></div> <div id="sections"></div>';
}
return __p;
};

},{}],90:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<h3 class="cs-pane-section-toggle">'+
((__t=( _section_title ))==null?'':__t)+
'</h3> <div class="cs-pane-section"> <ul class="cs-controls"></ul> </div>';
}
return __p;
};

},{}],91:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<p>'+
((__t=( l18n('htmlhint-intro') ))==null?'':__t)+
'</p> <ul>';
 _.each( errors, function(item) { 
__p+=' <li>'+
((__t=( l18n('htmlhint-' + item ) ))==null?'':__t)+
'</li> ';
 }); 
__p+='</ul>';
}
return __p;
};

},{}],92:[function(require,module,exports){
var media = wp.media;
var l10n = media.view.l10n;
wp.media.view.MediaFrame.Cornerstone = wp.media.view.MediaFrame.Post.extend({
	createStates: function() {
		var options = this.options;

		this.states.add( [

			// Main states.
			new media.controller.Library({
				id:         'insert',
				title:      l10n.insertMediaTitle,
				priority:   20,
				toolbar:    'main-insert',
				filterable: 'all',
				library:    media.query( options.library ),
				multiple:   options.multiple ? 'reset' : false,
				editable:   true,

				// If the user isn't allowed to edit fields,
				// can they still edit it locally?
				allowLocalEdits: true,

				// Show the attachment display settings.
				displaySettings: false,

				// Update user settings when users adjust the
				// attachment display settings.
				displayUserSettings: true

			}),

			// new media.controller.Library({
			//   id:         'gallery',
			//   title:      l10n.createGalleryTitle,
			//   priority:   40,
			//   toolbar:    'main-gallery',
			//   filterable: 'uploaded',
			//   multiple:   'add',
			//   editable:   false,

			//   library:  media.query( _.defaults({
			//     type: 'image'
			//   }, options.library ) )
			// }),

			// Embed states.
			new media.controller.Embed( { metadata: options.metadata } ),

			new media.controller.EditImage( { model: options.editImage } ),

			// Gallery states.
			// new media.controller.GalleryEdit({
			//   library: options.selection,
			//   editing: options.editing,
			//   menu:    'gallery'
			// }),

			// new media.controller.GalleryAdd(),

			// new media.controller.Library({
			//   id:         'playlist',
			//   title:      l10n.createPlaylistTitle,
			//   priority:   60,
			//   toolbar:    'main-playlist',
			//   filterable: 'uploaded',
			//   multiple:   'add',
			//   editable:   false,

			//   library:  media.query( _.defaults({
			//     type: 'audio'
			//   }, options.library ) )
			// }),

			// // Playlist states.
			// new media.controller.CollectionEdit({
			//   type: 'audio',
			//   collectionType: 'playlist',
			//   title:          l10n.editPlaylistTitle,
			//   SettingsView:   media.view.Settings.Playlist,
			//   library:        options.selection,
			//   editing:        options.editing,
			//   menu:           'playlist',
			//   dragInfoText:   l10n.playlistDragInfo,
			//   dragInfo:       false
			// }),

			// new media.controller.CollectionAdd({
			//   type: 'audio',
			//   collectionType: 'playlist',
			//   title: l10n.addToPlaylistTitle
			// }),

			// new media.controller.Library({
			//   id:         'video-playlist',
			//   title:      l10n.createVideoPlaylistTitle,
			//   priority:   60,
			//   toolbar:    'main-video-playlist',
			//   filterable: 'uploaded',
			//   multiple:   'add',
			//   editable:   false,

			//   library:  media.query( _.defaults({
			//     type: 'video'
			//   }, options.library ) )
			// }),

			// new media.controller.CollectionEdit({
			//   type: 'video',
			//   collectionType: 'playlist',
			//   title:          l10n.editVideoPlaylistTitle,
			//   SettingsView:   media.view.Settings.Playlist,
			//   library:        options.selection,
			//   editing:        options.editing,
			//   menu:           'video-playlist',
			//   dragInfoText:   l10n.videoPlaylistDragInfo,
			//   dragInfo:       false
			// }),

			// new media.controller.CollectionAdd({
			//   type: 'video',
			//   collectionType: 'playlist',
			//   title: l10n.addToVideoPlaylistTitle
			// })
		]);

		if ( media.view.settings.post.featuredImageId ) {
			this.states.add( new media.controller.FeaturedImage() );
		}
	}

} );

//   initialize: function() {
//     wp.media.view.MediaFrame.prototype.initialize.apply( this, arguments );

//     _.defaults( this.options, {
//       multiple:  true,
//       editing:   false,
//       state:  'insert'
//     });

//     this.createSelection();
//     this.createStates();
//     this.bindHandlers();
//     this.createIframeStates();
//   },

//   createStates: function() {
//   var options = this.options;

//   // Add the default states.
//   this.states.add([
//     // Main states.
//     new wp.media.controller.Library({
//     id:   'insert',
//     title:  'Insert Media',
//     priority:   20,
//     toolbar:  'main-insert',
//     filterable: 'image',
//     library:  wp.media.query( options.library ),
//     multiple:   options.multiple ? 'reset' : false,
//     editable:   true,

//     // If the user isn't allowed to edit fields,
//     // can they still edit it locally?
//     allowLocalEdits: true,

//     // Show the attachment display settings.
//     displaySettings: true,
//     // Update user settings when users adjust the
//     // attachment display settings.
//     displayUserSettings: true
//     }),

//     // Embed states.
//     new wp.media.controller.Embed(),
//   ]);


//   if ( wp.media.view.settings.post.featuredImageId ) {
//     this.states.add( new wp.media.controller.FeaturedImage() );
//   }
//   },

//   bindHandlers: function() {
//   // from Select
//   this.on( 'router:create:browse', this.createRouter, this );
//   this.on( 'router:render:browse', this.browseRouter, this );
//   this.on( 'content:create:browse', this.browseContent, this );
//   this.on( 'content:render:upload', this.uploadContent, this );
//   this.on( 'toolbar:create:select', this.createSelectToolbar, this );
//   //

//   this.on( 'menu:create:gallery', this.createMenu, this );
//   this.on( 'toolbar:create:main-insert', this.createToolbar, this );
//   this.on( 'toolbar:create:main-gallery', this.createToolbar, this );
//   this.on( 'toolbar:create:featured-image', this.featuredImageToolbar, this );
//   this.on( 'toolbar:create:main-embed', this.mainEmbedToolbar, this );

//   var handlers = {
//     menu: {
//       'default': 'mainMenu'
//     },

//     content: {
//       'embed':    'embedContent',
//       'edit-selection': 'editSelectionContent'
//     },

//     toolbar: {
//       'main-insert':  'mainInsertToolbar'
//     }
//     };

//   _.each( handlers, function( regionHandlers, region ) {
//     _.each( regionHandlers, function( callback, handler ) {
//     this.on( region + ':render:' + handler, this[ callback ], this );
//     }, this );
//   }, this );
//   },

//   // Menus
//   mainMenu: function( view ) {
//   view.set({
//     'library-separator': new wp.media.View({
//     className: 'separator',
//     priority: 100
//     })
//   });
//   },

//   // Content
//   embedContent: function() {
//   var view = new wp.media.view.Embed({
//     controller: this,
//     model:  this.state()
//   }).render();

//   this.content.set( view );
//   view.url.focus();
//   },

//   editSelectionContent: function() {
//   var state = this.state(),
//     selection = state.get('selection'),
//     view;

//   view = new wp.media.view.AttachmentsBrowser({
//     controller: this,
//     collection: selection,
//     selection:  selection,
//     model:  state,
//     sortable:   true,
//     search:   false,
//     dragInfo:   true,

//     AttachmentView: wp.media.view.Attachment.EditSelection
//   }).render();

//   view.toolbar.set( 'backToLibrary', {
//     text:   'Return to Library',
//     priority: -100,

//     click: function() {
//     this.controller.content.mode('browse');
//     }
//   });

//   // Browse our library of attachments.
//   this.content.set( view );
//   },

//   // Toolbars
//   selectionStatusToolbar: function( view ) {
//   var editable = this.state().get('editable');

//   view.set( 'selection', new wp.media.view.Selection({
//     controller: this,
//     collection: this.state().get('selection'),
//     priority:   -40,

//     // If the selection is editable, pass the callback to
//     // switch the content mode.
//     editable: editable && function() {
//     this.controller.content.mode('edit-selection');
//     }
//   }).render() );
//   },

//   mainInsertToolbar: function( view ) {
//   var controller = this;

//   this.selectionStatusToolbar( view );

//   view.set( 'insert', {
//     style:  'primary',
//     priority: 80,
//     text:   'Select Image',
//     requires: { selection: true },

//     click: function() {
//     var state = controller.state(),
//       selection = state.get('selection');

//     controller.close();
//     state.trigger( 'insert', selection ).reset();
//     }
//   });
//   },

//   featuredImageToolbar: function( toolbar ) {
//   this.createSelectToolbar( toolbar, {
//     text:  'Set Featured Image',
//     state: this.options.state || 'upload'
//   });
//   },

//   mainEmbedToolbar: function( toolbar ) {
//   toolbar.view = new wp.media.view.Toolbar.Embed({
//     controller: this,
//     text: 'Insert Image'
//   });
//   }

// });
},{}],93:[function(require,module,exports){
var handlers = {

	editorCloned: function( clone, original, type ) {

		if ( 'mirror' != type ) {
			Backbone.$( original ).trigger( 'dragula:cloned', clone );
			return;
		}

		Backbone.$( original ).trigger( 'dragula:mirror', clone );

		_.defer( function() {
			Backbone.$( original ).trigger( 'dragula:start', clone );
			cs.global.trigger( 'dragging', true );
		} );

	},

	// previewMirror: function( clone, original, type ) {
	// 	if (type != 'mirror') return;
	// 	Backbone.$(original).trigger( 'dragula:mirror', clone );
	// },

	skeletonEnd: function( el ) {
		cs.global.trigger( 'dragging', false );
		cs.events.trigger( 'skeleton:dragging', false );
	},

	skeletonStart: function( el, source ) {
		var $el = Backbone.$( el );
		cs.events.trigger( 'skeleton:dragging', true, $el.attr( 'data-element-type' ) );
		$el.trigger( 'dragula:lift' );
		Backbone.$( source ).trigger( 'dragula:lift:child' );
	},

	cancel: function( el, container, source ) {
		Backbone.$( el ).trigger( 'dragula:dragend' );
	},

	drop: function( el, target, source, sibling ) {
		Backbone.$( el ).trigger( 'dragula:drop', [ target, source, sibling ] );
		Backbone.$( target ).trigger( 'dragula:receive', [ el, source, sibling ] );
	},

	over: function( el, container, source ) {
		Backbone.$( container ).trigger( 'dragula:over' );
		Backbone.$( source ).trigger( 'dragula:source:over' );
	},

	out: function( el, container, source ) {
		Backbone.$( container ).trigger( 'dragula:out' );
	},

	shadow: function( el, container, source ) {
		Backbone.$( source ).trigger( 'dragula:shadow' );
	}

};

module.exports = handlers;

},{}],94:[function(require,module,exports){
( function( $ ) {

		var $shadow = $( '<div class="shadow-height x-section" style="position:absolute;top:200%;visibility:hidden;"></div>' );

		$.fn.shadowHeight = function() {

			if ( this.length < 1 ) return;

			var $el = this.length > 1 ? this.eq( 0 ) : this;
			var $copy = $el.clone();

			$shadow.empty().appendTo( '#cs-content' );
			$shadow.append( $copy );

			var height = $copy.outerHeight();

			if ( height < 1 ) {
				$copy.addClass( 'cf' );
				height = $copy.outerHeight();
			}

			$shadow.detach();
			$copy.remove();

			return height;

		};

		$.fn.csPointInsideElement = function( x, y ) {

			if ( this.length < 1 ) return;
			var $el = this.length > 1 ? this.eq( 0 ) : this;

			var offset = $el.offset();

			var hor = ( x > offset.left && x < offset.left + $el.width() );
			var vert = ( y > offset.top && y < offset.top + $el.height() );

			return ( hor && vert );

		};

	$.fn.focusEnd = function() {

		return this.each(function() {

			$( this ).focus();

			// Move to end if supported by browser and element
			if ( 'function' === typeof this.setSelectionRange ) {
				var end = $( this ).val().length * 2;
				this.setSelectionRange( end, end );
				this.scrollTop = 99999999;
			}

		});

	};

	$.fn.cleanPaste = function() {

		function cleanContent( str ) {
			return str.replace( /\u200B/g, '' );
		}

		function handler( e ) {

			try {

				var content = cleanContent( e.originalEvent.clipboardData.getData( 'text' ) );
				var before = this.value.substr( 0, this.selectionStart ) + content;
				var after = this.value.substr( this.selectionEnd, this.value.length );
				this.value = before + after;
				this.setSelectionRange( before.length, before.length );
				e.preventDefault();
				$( this ).change();

			} catch ( e ) {

			}

		}

		return this.each(function() {
			$( this ).off( 'paste', handler ).on( 'paste', handler );
		});

	};

})( jQuery );

},{}],95:[function(require,module,exports){
module.exports.reduceFractions = function( replace ) {
	reductions = [ { f: '2\/4', r: '1/2' }, { f: '2\/6', r: '1/3' }, { f:'3\/6', r: '1/2' }, { f:'4\/6', r: '2/3' }];
	var string = replace;
	_( reductions ).each( function( reducer ) {
		var re = new RegExp( reducer.f, 'g' );
		string = string.replace( re, reducer.r );
	});
	return string;
};

module.exports.layoutIsValid = function( layout ) {
	return _([ '1/1', '1/2 + 1/2', '2/3 + 1/3', '1/3 + 2/3', '1/3 + 1/3 + 1/3', '3/4 + 1/4', '1/4 + 3/4', '1/2 + 1/2', '1/2 + 1/4 + 1/4', '1/4 + 1/2 + 1/4', '1/4 + 1/4 + 1/2', '1/4 + 1/4 + 1/4 + 1/4', '4/5 + 1/5', '1/5 + 4/5', '3/5 + 2/5', '2/5 + 3/5', '3/5 + 1/5 + 1/5', '1/5 + 3/5 + 1/5', '1/5 + 1/5 + 3/5', '2/5 + 2/5 + 1/5', '2/5 + 1/5 + 2/5', '1/5 + 2/5 + 2/5', '2/5 + 1/5 + 1/5 + 1/5', '1/5 + 2/5 + 1/5 + 1/5', '1/5 + 1/5 + 2/5 + 1/5', '1/5 + 1/5 + 1/5 + 2/5', '1/5 + 1/5 + 1/5 + 1/5 + 1/5', '5/6 + 1/6', '1/6 + 5/6', '2/3 + 1/3', '1/3 + 2/3', '2/3 + 1/6 + 1/6', '1/6 + 2/3 + 1/6', '1/6 + 1/6 + 2/3', '1/2 + 1/2', '1/2 + 1/3 + 1/6', '1/2 + 1/6 + 1/3', '1/3 + 1/2 + 1/6', '1/3 + 1/6 + 1/2', '1/6 + 1/2 + 1/3', '1/6 + 1/3 + 1/2', '1/2 + 1/6 + 1/6 + 1/6', '1/6 + 1/2 + 1/6 + 1/6', '1/6 + 1/6 + 1/2 + 1/6', '1/6 + 1/6 + 1/6 + 1/2', '1/3 + 1/3 + 1/3', '1/3 + 1/3 + 1/6 + 1/6', '1/3 + 1/6 + 1/3 + 1/6', '1/3 + 1/6 + 1/6 + 1/3', '1/6 + 1/3 + 1/3 + 1/6', '1/6 + 1/3 + 1/6 + 1/3', '1/6 + 1/6 + 1/3 + 1/3', '1/3 + 1/6 + 1/6 + 1/6 + 1/6', '1/6 + 1/3 + 1/6 + 1/6 + 1/6', '1/6 + 1/6 + 1/3 + 1/6 + 1/6', '1/6 + 1/6 + 1/6 + 1/3 + 1/6', '1/6 + 1/6 + 1/6 + 1/6 + 1/3', '1/6 + 1/6 + 1/6 + 1/6 + 1/6 + 1/6' ])
		.contains( layout );
};

},{}],96:[function(require,module,exports){
String.prototype.replaceAll = function( find, replace ) {
	return this.replace( new RegExp( find.replace( /([.*+?^=!:${}()|\[\]\/\\])/g, '\\$1' ), 'g' ), replace );
};

},{}],97:[function(require,module,exports){
module.exports = CS.Mn.CompositeView.extend({
	tagName: 'li',
	template: 'controls/base',
	controlName: 'default',
	bindings: {},
	canCompact: true,
	baseEvents: {
    'click button.cs-expand-control': 'expandControl'
  },
	controlEvents: {},
	events: function(){
		return _.extend( this.baseEvents, this.controlEvents );
	},
	constructor: function() {

		/**
		 * Set class name base on control name
		 */
		this.className = 'cs-control cs-control-' + this.controlName;

		/**
		 * Call Super (Parent constructors, and eventually initialize)
		 */
    CS.Mn.CompositeView.apply( this, arguments );

    this.configureProxy();
    this.listenTo( this.model, 'set:proxy', this.configureProxy );

    this.on('render', this.baseRender );

    this.listenTo( cs.options, 'help:text', this.render );

    /**
     * Ensure a controlTemplate is defined
     * Native controls can derive a template from their controlName
     * External controls will need to explicitly define a template
     */
    if ( !_.isFunction( this.controlTemplate ) && !_.isFunction( cs.template( this.controlTemplate ) ) ) {
    	this.controlTemplate = 'controls/' + this.controlName;
    }

	},

	configureProxy: function() {

		if ( this.proxy )
			this.stopListening( this.proxy );

    this.proxy = this.model.proxy || null;
    if ( _.isNull( this.proxy ) ) return;

    var key = this.model.get( 'key' );
    if ( this.proxy.definition && ! this.proxy.has( key ) && ! _.contains(['elements', 'disabled'], key ) ) {
			cs.warn( 'Element [' + this.proxy.definition.get( 'name' ) + '] missing [' + key + '] attribute.', this.proxy );
			return;
    }

    var conditions = this.model.get('condition');
    if ( conditions ) {
    	_.each( _.keys( conditions ) , function( item ) {

				if ( item.indexOf(':not') == item.length - 4 ) item = item.replace( ':not', '' );
        if ( item.indexOf('parent:') == 0 ) item = item.replace( 'parent:', '' );
    		this.listenTo(this.proxy, 'change:' + item, this.toggleVisibility );

    	}, this );
    }

  	this.triggerMethod( 'proxy:ready' );

	},

	toggleVisibility: function() {

		var visible = true;
		var conditions = this.model.get('condition');

		if ( !_.isNull(this.proxy) && conditions ) {

			// We want this to be empty, so returning false for all items
			// means all conditions have been met
			var remainingConditions = _.filter( _.keys( conditions ), _.bind( function( conditionName ) {

				var conditionValue = conditions[conditionName];

				var negate = ( conditionName.indexOf( ':not' ) == Math.abs( conditionName.length - 4 ) );
				if ( negate ) conditionName = conditionName.replace( ':not', '' );

        if ( conditionName.indexOf('parent:') == 0 ) {
          source = this.proxy.getSourceParent().toProxy();
          conditionName = conditionName.replace('parent:','');
        } else {
          source = this.proxy;
        }

        var controlValue = source.get(conditionName);

	  		var check = ( _.isArray( conditionValue ) ) ? _.contains( conditionValue, controlValue ) : ( controlValue == conditionValue );

	  		return (negate) ? check : !check;

	    }, this ));

			visible = _.isEmpty( remainingConditions );

  	}

  	var hidden = this.$el.hasClass( 'hide' );
  	var changed = ( (hidden && visible) || ( !hidden && !visible ) );

		this.$el.toggleClass( 'hide', !visible );

		if (changed) {
			cs.navigate.trigger( 'scrollbar:update' );
		}

		this.triggerMethod( 'custom:visibility', visible, changed );

	},

	baseRender: function() {

		this.triggerMethod('before:base:render');

		var ui = this.model.get('ui')
		var options = this.model.get('options')

		if ( ( !ui.title && this.canCompact ) || options.compact ) this.$el.addClass('cs-control-compact');
		if ( ui.divider || this.divider ) this.$el.addClass('cs-control-divider');

		this.$el.attr('data-name', this.model.get('name') );
		this.toggleVisibility();
		this.stickitBindings();
		this.triggerMethod('after:base:render');

		this.$('input,textarea').cleanPaste();

	},

	stickitBindings: function() {

		var selector = this.bindingSelector || 'input[type=hidden]';
		var config = { observe: this.model.get('key') };

		if (this.binding)
			_.extend( config, this.binding )

  	this.addBinding( this.proxy, selector, config);
  	this.stickit( this.proxy );

	},


	/**
	 * Provide additional data to the template
	 */
	serializeData: function() {

		var options = this.model.get('options');
		var ui = _.clone( this.model.get('ui') );

		if ( ui.message && ui.message.indexOf('[HelpText]') == 0) {
			ui.message = ui.message.replace('[HelpText]','');
		}

		if ( !cs.options.request( 'help:text' ) && options.helpText !== false ) {
			ui.message = false;
		}

		var data = _.extend( CS.Mn.CompositeView.prototype.serializeData.apply(this,arguments), {
			controlTemplate: this.controlTemplate,
			controlType: this.model.get('type'),
			ui: ui
		});

		if ( data.ui.title )
			data.ui.title = this.replacePlaceholders( data.ui.title );

		if ( data.ui.tooltip )
			data.ui.tooltip = this.replacePlaceholders( data.ui.tooltip );

		if ( data.ui.message )
			data.ui.message = this.replacePlaceholders( data.ui.message );

		if ( this.proxy )
			_.extend( data, this.proxy.toJSON() );

		if ( _.isFunction( this.controlData ) )
			_.extend( data, this.controlData() );

		return data;

	},

	baseTextReplacements: {
		'%%element-name%%': function() {
			if ( !this.proxy) return '';
			return this.proxy.definition.get('name');
		},
		'%%element-type%%': function() {
			if ( !this.proxy) return '';
			return this.proxy.get('_type');
		},
		'%%icon%%': function() {
			if ( !this.proxy) return '';
			return cs.icon( this.proxy.definition.get('icon') );
		},
		'%%icon-nav-elements-solid%%': function() {
			return cs.icon( 'interface/nav-elements-solid' );
		},
		'%%icon-nav-settings-solid%%': function() {
			return cs.icon( 'interface/nav-settings-solid' );
		}
	},

	replacePlaceholders: function ( text ) {

		_.each( _.extend( this.textReplacements || {}, this.baseTextReplacements ), _.bind( function( callback, tag ) {
			if ( text.indexOf( tag ) == -1 ) return;
			text = text.replace( new RegExp( tag, 'g'), callback.apply(this) );
		}, this) );

		return text;
	},

	notLiveTrigger: function() {
		var options = this.model.get('options');
		if (options.notLive) {
			cs.data.trigger('control:not:live', this.model.proxy.get('name') + '_' + this.model.get('name'), options.notLive);
		}
	},

  expandControl: function() {

    this.triggerMethod( 'before:expand' );
    this.$el.addClass( 'cs-control-expanded' );
    cs.events.trigger( 'expand:control', this.proxy.getSource(), this.model.get('name'), this );

    this.listenToOnce( cs.events, 'expand:close', this.expandClose );
  },

  expandClose: function() {

  	this.proxy.refresh();
  	this.$el.removeClass( 'cs-control-expanded' );
  	this.render();
  	this.triggerMethod( 'after:expand' );
    // this.$el.removeClass( 'cs-control-expanded' ).one( 'transitionend', _.bind( function() {
    // 	this.render();
    //
    // }, this ) );

  },

});
},{}],98:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-breadcrumbs cs-control-divider',
  template: 'inspector/breadcrumbs',
  controlName: 'breadcrumbs',
  events: {
    'click button': 'inspect',
    'mouseover button': 'mouseOver',
    'mouseout button': 'mouseOut',
  },

  initialize: function() {
    this.levels = this.findLevels( [], this.model.proxy.getSource() );
  },

  mouseOver: function( e ) {
    var level = this.buttonLevel( e );
    if ( level  ) level.model.trigger( 'observe:start' );
  },

  mouseOut: function( e ) {
    var level = this.buttonLevel( e );
    if ( level  ) level.model.trigger( 'observe:end' );
  },

  inspect: function( e ) {
    var level = this.buttonLevel( e );
    if ( !level ) return;
    cs.events.trigger( 'inspect:element', level.model, false );
  },

  buttonLevel: function( e ) {
    return this.levels[ parseInt( this.$(e.currentTarget).data('level') ) ];
  },

  findLevels: function( levels, model ) {

    if ( model.get('_type') != 'section' && model.collection && model.collection.parent ) {
      levels = this.findLevels( levels, model.collection.parent );
    }

    var item = {
    	label: model.definition.get('ui').title,
    	model: model
    };

    switch ( model.get('_type') ) {
      case 'section':
        item.title = model.get('title');
        item.label = cs.l18n('section');
        break;
      case 'row':
        item.label = cs.l18n('row');
        break;
      case 'column':
        item.label = cs.l18n('column');
        break;
    }

    levels.push( item );

    return levels;
  },

  serializeData: function() {
    return {
      items: _.first( this.levels, 4 ),
      count: this.levels.length,
      rtl: cs.config( 'isRTL' )
    }
  },

  onRender: function( ) {
  	if ( this.levels.length <= 1 && !this.levels[0].title ) this.$el.toggleClass( 'hide', true );
  }

});
},{}],99:[function(require,module,exports){
	module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'choose',
	binding: {
    initialize: function($el, model, options) {

      var localOpts = this.model.get('options')

    	/**
    	 * Update Model when a new option is clicked
    	 */
      this.$('li').on('click', _.bind( function (e) {

        var choice = this.$(e.currentTarget).data('choice');

        if ( !_.isUndefined( localOpts.offValue ) && choice == model.get( options.observe ) ) {
          choice = localOpts.offValue;
        }

      	model.set( options.observe, choice );
      }, this ) );

      /**
       * Handler to set the active state based on the model value
       */
    	var setActive = _.bind( function( model, value ) {
        var selection = (options.observe) ? value : null;
        if ( !selection && selection != "" ) {
          selection = (localOpts.choices && localOpts.choices.length > 0) ? localOpts.choices[0].value : 'none'
        }

    		this.$('li').removeClass('active').siblings('[data-choice=' + ( (selection == '') ? 'none' : selection ) + ']').addClass('active');
    	}, this );

    	/**
    	 * Set the initial active state, then listen to model changes to change the state later
    	 */
    	setActive( model, model.get( options.observe ) );
    	this.listenTo(model, 'change:' + options.observe, setActive );

    }
	},
  onBeforeRender: function() {

    /**
     * Make sure we have a valid number of columns.
     */
    var options = this.model.get('options');
    if ( !_.contains(['2', '3', '4', '5'], options.columns ) ) {
      options.columns = '2';
      this.model.set('options',options);
    }
  }
});
},{}],100:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'div',
	className: 'cs-control-external cs-control-code-editor',
	template: _.template('<textarea></textarea>'),

	initialize: function(){

		this.listenTo( cs.navigate, 'open:code:editor', function( name ){
			if (name == this.model.get('key')){
				this.$('textarea').csCodeEditorShow();
			}
		});

	},

	onRender: function() {
		var localOpts, options;

		this.$('textarea').val(this.model.proxy.get(this.model.get('key')));

		localOpts = this.model.get('options');

		options = localOpts.settings || {};

	 	options = _.extend( options, {
			change: _.bind(function( cm ){
				this.model.proxy.set(this.model.get('key'),cm.doc.getValue());
			}, this )
		});

		_.defer(_.bind(function(){
			this.$el.detach();
			Backbone.$('body').append( this.$el );
			this.$('textarea').csCodeEditor( options );
		}, this ) );

	}

});

},{}],101:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({

	controlName: 'color',
	bindingSelector: 'input[type=text].cs-color-input',

	onAfterBaseRender: function() {
		var opts = this.model.get( 'options' ) || {};

		var options = {
			outputFormat: opts.output_format || null
		};

		this.$( '.cs-color-input' ).huebert( options );
	}

});

},{}],102:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-actions cs-control-divider',
  template: 'inspector/column-actions',
  controlName: 'actions',
	ui: {
    'confirm': '.action.erase',
    'layout' : '.action.manage-layout',
  },

  events: {
    'click @ui.layout': 'layout'
  },

  behaviors: {
    Confirm: {
      message: cs.l18n('columns-erase-confirm'),
    }
  },
  canQuickConfirm: true,

  initialize: function( options ) {
    this.proxy = this.model.proxy || null;
  	this.selected = options.selected || undefined;
  },

  layout: function() {
  	cs.events.trigger( 'inspect:layout', this.proxy.getSource(), { navigate: true } );
  },

  onConfirmAccept: function() {
    cs.elements.trigger( 'erase', { model: this.proxy.getSource() } );
  },

});
},{}],103:[function(require,module,exports){
module.exports = CS.Mn.CollectionView.extend({
	tagName: 'ul',

	className: 'cs-controls',

	initialize: function() {
		this.repaint = _.debounce( _.bind( function(){

			try {
				this.render()
			} catch (e) {
				if ( e.name == 'ViewDestroyedError' ) return;
				console.log('Cornerstone Render Exception', e );
			}

		}, this ), 4 );
		this.listenTo( this.collection, 'add', this.repaint );
		this.listenTo( this.collection, 'remove', this.repaint );
		this.listenTo( this.collection, 'reset', this.repaint );
		this.listenTo( cs.navigate, 'auto:focus', this.autoFocus );
	},

	getChildView: function( item ) { return cs.controlLookup( item.get('type') ); },

	onRender: function() {
		_.defer( _.bind( function(){
			this.$el.toggleClass( 'empty', this.collection.isEmpty() );
			this.autoFocus();
		}, this ) );
	},

	autoFocus: function( ) {

  	var name = cs.navigate.request( 'auto:focus' );

  	if ( false === name )
  		return;

  	var $control = this.$('[data-name="' + name + '"]');

  	if ( $control.length > 0 && !$control.hasClass( 'cs-control-expanded' ) ) {
  		cs.navigate.reply(false);
  		$control.find('input[type="text"],textarea').focusEnd();
  	}

  }

})
},{}],104:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	template: 'controls/custom-markup',
	controlName: 'custom-markup',
	controlData: function() {
		var opts = this.model.get('options');
		var message = opts.html || '';
		return { message: message };
	},
});
},{}],105:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({

	controlName: 'date',

	binding: {
		initialize: function($el, model, options) {

			var opts = this.model.get( 'options' );

			// Ensure we have at least one available format
			var available_formats = opts.available_formats;
			if (!available_formats || !available_formats.length) {
				var item = opts.default_format || 'Do MMMM YYYY';
				available_formats = [item];
			}

			var $input = this.$('.cs-date-input');
			var $formatSelect = this.$('.cs-date-format select');

			this.picker = new Cornerstone.Vendor.Pikaday( {
        field: $input[0],
        bound: false,
        container: this.$('.cs-date-picker-entry')[0],
        theme: 'cs-date-picker',
        isRTL: cs.config('isRTL'),
        i18n: this.localize(),
        onSelect: pickerSelect
	    });

	    if ( !opts.choose_format ) this.$('.cs-date-format').hide();

	    // Update when a new format is selected
	    $formatSelect.on('change', formatSelect );

	    function pickerSelect( date ) {
				var current = unwrapValue( model.get( options.observe ) )
				var combined = date + opts.delimiter + current.format;
				model.set( options.observe, combined );
			}



	    function formatSelect() {
				var current = unwrapValue( model.get( options.observe ) )
				var combined = current.date + opts.delimiter + validateFormat( Backbone.$(this).val() );
				model.set( options.observe, combined );
			}

			function unwrapValue( value ) {

				var split, moment, selectedFormat;

				if ( value ) {

					var split = value.split( opts.delimiter );

					if ( split[0] ) {
						moment = Cornerstone.Vendor.moment( split[0] );
						moment = ( moment.isValid() ) ? moment : false;
					}

					if ( split[1] ) {
						selectedFormat = split[1];
					}

				}

				if ( !moment ) {
					moment = Cornerstone.Vendor.moment()
				}

				return { date: moment.toString(), format: validateFormat( selectedFormat ), moment: moment };

			}


			function validateFormat( format ) {
				if ( _.contains( available_formats, format ) )
					return format;
				return opts.default_format || 'Do MMMM YYYY';
			}

			/**
			* Handler to set the active state based on the model value
			*/
			var setActive = _.bind( function( model, value ) {

				value = unwrapValue( value );
				var date = Cornerstone.Vendor.moment( value.date, value.format );

				$input.val( value.moment.format( value.format ) );

				this.picker.config( { format: value.format } );
				this.picker.setMoment( value.moment, true );

				// Generate options for select
				var html = _.reduce( available_formats, function( memo, item ) {
					var selected = ( item == value.format) ? ' selected' : '';
					return memo + '<option value="' + item + '"' + selected +'>' + value.moment.format( item ) + '</option>';
				}, '');

				$formatSelect.empty();
				$formatSelect.append(Backbone.$(html));

			}, this );

			/**
			* Set the initial active state, then listen to model changes to change the state later
			*/
			setActive( model, model.get( options.observe ) );
			this.listenTo(model, 'change:' + options.observe, setActive );

		}
	},

	localize: function() {
		return {
	    previousMonth : cs.l18n('prev-month'),
	    nextMonth     : cs.l18n('next-month'),
	    months        : cs.l18n('moment-months').split('_'),
	    weekdays      : cs.l18n('moment-weekdays').split('_'),
	    weekdaysShort : cs.l18n('moment-weekdays-short').split('_')
		}
	},

	onBeforeDestroy: function() {
		this.picker.destroy();
	}

});
},{}],106:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'dimensions',
	binding: {
	  initialize: function($el, model, options) {

      var localOpts = this.model.get('options');

      var $field;
      if (localOpts.lock) {
        _.each( localOpts.lock, function( value, key ) {
          this.$('[data-edge=' + key + ']').prop( 'disabled', true ).val( value );
        }, this );
      }

      this.$('button.save')

      var currentVal = function() {
      	var val = model.get(options.observe);
      	if ( val ) return _.clone( val );
      	return [ '0px', '0px', '0px', '0px', 'unlinked' ];
      }

      /**
       * Update value for link toggle
       */
      this.$('button.cs-link-dimensions').click(function(){
        var val = currentVal();
        state = ( val[4] == 'linked') ? 'unlinked' : 'linked';

        // When linking, make everything the first value for visual feedback
        if ( state == 'linked') {
          val = _.map(val, function(){ return val[0]; });
        }

        val[4] = state;

        if (localOpts.lock) {
          _.each( localOpts.lock, function( value, key ) {
            if (key == 'top' )    val[0] = value;
            if (key == 'right' )  val[1] = value;
            if (key == 'bottom' ) val[2] = value;
            if (key == 'left' )   val[3] = value;
          }, this );
        }

        model.set( options.observe, val );
      });

    	/**
    	 * Update Model when a new option is clicked
    	 */
    	this.$('[data-edge]').on('change keyup', _.bind( function (e) {

        $changed = this.$(e.currentTarget);
    		var val = currentVal();

        var update = $changed.val().trim();
        if ( update == '' ) update = '0px';

        if ( val[4] == 'linked' ) {
          val = _.map(val, function(){ return update; });
          val[4] = 'linked';
        } else {
          val[ $changed.parent().index() ] = update;
          val[4] = 'unlinked';
        }

        if (localOpts.lock) {
          _.each( localOpts.lock, function( value, key ) {
            if (key == 'top' )    val[0] = value;
            if (key == 'right' )  val[1] = value;
            if (key == 'bottom' ) val[2] = value;
            if (key == 'left' )   val[3] = value;
          }, this );
        }


    		model.set( options.observe, val );


    	}, this ) );

      /**
       * Handler to set the active state based on the model value
       */
    	var setValues = _.bind( function( model, val ) {

    		$top = this.$('[data-edge=top]');
        $right = this.$('[data-edge=right]');
        $bottom = this.$('[data-edge=bottom]');
        $left = this.$('[data-edge=left]')

        if ( $top.val() != val[0] ) $top.val( val[0] );
        if ( $right.val() != val[1] ) $right.val( val[1] );
        if ( $bottom.val() != val[2] ) $bottom.val( val[2] );
        if ( $left.val() != val[3] ) $left.val( val[3] );

        if ( $top.val() != val[0] ) $top.val( val[0] );
        if ( $right.val() != val[1] ) $right.val( val[1] );
        if ( $bottom.val() != val[2] ) $bottom.val( val[2] );
        if ( $left.val() != val[3] ) $left.val( val[3] );

        this.$('button.cs-link-dimensions').toggleClass( 'active', ( val[4] == 'linked' ) );

    	}, this );

    	/**
    	 * Set the initial active state, then listen to model changes to change the state later
    	 */
    	setValues( model, currentVal() );
    	this.listenTo(model, 'change:' + options.observe, setValues );

    }
	}
});
},{}],107:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
  controlName: 'editor',
  controlTemplate: 'controls/textarea',
  bindingSelector: 'textarea.cs-wp-editor',
  binding: {
    events: [ 'keyup', 'change', 'cut', 'paste', 'focus' ],
    onSet: function(value) { return this.textReplace( value ); },
    onGet: function(value) { return this.textReplace( value ); }
  },
  onProxyReady: function() {

    this.editorID = 'cswpeditor' + this.cid;

    tinyMCEPreInit.mceInit[ this.editorID ] = _.clone(tinyMCEPreInit.mceInit['cswpeditor']);
    tinyMCEPreInit.mceInit[ this.editorID ].id = this.editorID;
    tinyMCEPreInit.mceInit[ this.editorID ].selector = '#' + this.editorID;

    tinyMCEPreInit.qtInit[ this.editorID ] = {
      buttons: "strong,em,del,link,img,close",
      id: this.editorID,
    }

    tinyMCEPreInit.mceInit[ this.editorID ].setup = _.bind(function(editor) {

    	var update = _.debounce( _.bind( function() {
        editor.save(); // Commit editor contents to original textarea
        this.$('.cs-wp-editor').trigger('change'); // Trigger stickit
      }, this ), 150 )

      editor.on( 'keyup change NodeChange', update );

    }, this );

    this.markup = cs.config('editorMarkup')
      .replace( new RegExp('cswpeditor', 'g'), this.editorID )
      .replace( new RegExp('%%PLACEHOLDER%%', 'g'), this.proxy.get('content') );

  },

  updateContent: function() {

  },

  attachElContent: function(html) {
    this.$el.html( this.markup );
    this.$el.append( cs.template( 'controls/expand-control-button' )() );
    return this;
  },

  onRender: function() {

    // Convert Add Media button to icon only.
    this.$('.button.insert-media.add_media').html( '<span class="wp-media-buttons-icon"></span>' );

    // Strip 3rd party buttons
    this.$('.wp-media-buttons').children().not('#insert-media-button,#cs-insert-shortcode-button').detach();

    // Wait a cycle before initializing the editors.
    _.defer( _.bind( function() {

      // Initialize QuickTags with cloned settings, and set as the default mode.
      quicktags( tinyMCEPreInit.qtInit[this.editorID] );
      switchEditors.go( this.editorID, 'html' );
      wpActiveEditor = this.editorID;

      // Remove default instance after initializes. This allows reinitializion an unlimited amount of times.
      _.defer(function(){
        delete QTags.instances[0];
      });

    }, this ) );

  },

  onDestroy: function() {

    // Remove TinyMCE and QuickTags instances
    tinymce.EditorManager.execCommand('mceRemoveEditor',true, this.editorID );
    delete QTags.instances[this.editorID];

    // Cleanup PreInit data
    delete tinyMCEPreInit.mceInit[ this.editorID ];
    delete tinyMCEPreInit.qtInit[ this.editorID ];

  },

  replacements: {
    // '<!--nextpage-->': '<!--!nextpage-->',
    // '<!--more-->': '<!--!more-->'
  },

  textReplace: function( content ) {

    // _.each(this.replacements, function(replace, find){
    //   content = content.replaceAll( find, replace );
    // });

    return content;

  },

  onBeforeExpand: function() {
    switchEditors.go( this.editorID, 'html' );
  },

  onExpandedOpen: function() {

  }

});
},{}],108:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-actions cs-control-divider',
  template: 'inspector/element-actions',
  controlName: 'actions',
	ui: {
    'duplicate' : '.action.duplicate',
    'delete': '.action.delete'
  },

  events: {
  	'click @ui.duplicate': 'duplicate',
  	'click @ui.delete': 'elDelete'
  },

  initialize: function( options ) {
    this.proxy = this.model.proxy || null;
  	this.selected = options.selected || undefined;
  },

  duplicate: function() {
  	cs.global.trigger( 'element:duplicate', this.proxy.getSource() );
  },

  elDelete: function() {
		cs.global.trigger( 'element:delete', this.proxy.getSource() );
  },

});
},{}],109:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'icon-choose',
  childViewContainer: 'ul.cs-choose',
  childView: CS.Mn.ItemView.extend({
    tagName: 'li',
    template: 'controls/icon-choose-item'
  }),

  controlEvents: {
    'keyup .cs-search-input': 'search',
    'search .cs-search-input': 'search'
  },

  initialize: function() {
    this.iconData = cs.data.request('get:icons');
    this.filteredIcons = this.iconData;
    this.iconNames = cs.config( 'fontAwesome' );

    this.lazyUpdateSearch = _.debounce( this.updateSearch, 250 );
  },

  search: function() {
    this.lazyUpdateSearch( this.$('.cs-search-input').val().toLowerCase().trim() )
  },

  updateSearch: function( query ) {

    _.defer( _.bind( this.deferRender, this ) );

    if (query == '') {
      this.filteredIcons = this.iconData
      return;
    }

     var filtered = {};
     _.each( this.iconData, function( names, key ) {

      var score = _.reduce( names, function( memo, name ) {
        return memo + name.score( query );
      }, 0 );

      if ( score  > .5 )
        filtered[key] = names;
    } );

    this.filteredIcons = filtered;

  },


	binding: {
    initialize: function($el, model, options) {

      /**
       * Update Model when a new option is clicked
       */
      this.$('ul').on('click', 'li', _.bind( function (e) {

        var choice = this.$(e.currentTarget).data('choice');
        if ( choice == model.get( options.observe ) ) {
          model.set( options.observe, '' );
          return;
        }

        if (this.$prevIcon)
          this.$prevIcon.removeClass('active')

        this.$prevIcon = this.$(e.currentTarget)
        this.click = true;

        model.set( options.observe, choice );
        this.$(e.currentTarget).addClass('active');

      }, this ) );

      /**
       * Handler to set the active state based on the model value
       */
      var setActive = _.bind( function( model, selection ) {

        if ( this.$prevIcon )
          this.$prevIcon.removeClass('active');

        if ( !selection || selection == '' ) return;

        this.$prevIcon = this.$('.cs-icons-inner li[data-choice=' + selection + ']')
        this.$prevIcon.addClass('active');

        var pos = this.$prevIcon.position();

        if( !this.click && pos )
          this.$('.cs-icons-inner').scrollTop(pos.top).perfectScrollbar('update');

        this.click = false;

      }, this );

      /**
       * Set the initial active state, then listen to model changes to change the state later
       */

      //setActive( model, model.get( options.observe ), false );

      this.on('deferred:render', function(){
        setActive( model, model.get( options.observe ) );
      });

      this.listenTo(model, 'change:' + options.observe, setActive );


    }
  },

  onRender: function() {

  	this.$('ul.cs-choose').empty();

    var count = 0;
    _.each( this.filteredIcons, function( words, code ) {
      if ( count++ > 20) return;
      this.$('ul.cs-choose').append( cs.template('controls/icon-choose-item')({ code: code, choice: words[0], choices: words.join(' ') }) );
    }, this );

    this.$('.cs-icons-inner').perfectScrollbar({
      scrollYMarginOffset: 10,
      wheelPropagation: true
    });

    // Outputting the icons takes a moment, so let's do that pseudo-asynch
    _.defer( _.bind( this.deferRender, this ) );

  },

  deferRender: function() {

    this.$('ul.cs-choose').empty();

    _.each( this.filteredIcons, function( words, code ) {
      this.$('ul.cs-choose').append( cs.template('controls/icon-choose-item')({ code: code, choice: words[0], choices: words.join(' ') }) );
    }, this );

    if ( this.expanded ) {

    	var $outer = this.$( '.cs-icons-outer' );
    	$outer.removeClass( 'cs-expandable' ).addClass('cs-expanded');

    	var outerHeight = $outer.outerHeight();
    	var outerWidth = $outer.outerWidth();

  		$outer.css( {
  			'max-width': outerWidth - ( outerWidth % 50 ) + 2,
  			'max-height': outerHeight - ( ( outerHeight - 70 ) % 50 )
  		} );



    }

    this.$('.cs-icons-inner').perfectScrollbar('update');
    this.trigger('deferred:render');

  },

  onExpandedOpen: function() {
  	this.expanded = true;

		_.delay( _.bind( function(){
			this.$('.cs-search-input').focus();
		}, this ), 25 );

  },

});
},{}],110:[function(require,module,exports){

var ImageControl =  Cornerstone.ControlViews.Base.extend({
  controlName: 'image',
  binding: {
    initialize: function($el, model, options) {

      /**
       * Update Model when a new option is clicked
       */
      this.$('.cs-image').on('click', _.bind( function (e) {

        if ( !this.$(e.currentTarget).hasClass( 'empty' ) ) {
          model.set( options.observe, '' );
          return;
        }

        var uploader = ImageControl.uploader;

        uploader.off( 'insert' );
        uploader.off( 'select' );

        uploader.on( 'insert', _.bind( function() {
          var data = uploader.state().get( 'selection' ).first().toJSON();

          model.set( options.observe, data.url );
        }, this ) );

        uploader.on( 'select', function(){

          var state = uploader.state();

          if (state && state.get('id') == 'embed') {
            model.set( options.observe, state.props.get('url') );
          }

        });

        uploader.open();

      }, this ) );

      /**
       * Handler to set the active state based on the model value
       */
      var setActive = _.bind( function( model, image ) {

        var isEmpty = ( !image || '' == image );

        this.$('.cs-image').toggleClass( 'empty', isEmpty )
          .css( { backgroundImage: isEmpty ? 'none' : 'url(' + image + ')' } );

      }, this );

      /**
       * Set the initial active state, then listen to model changes to change the state later
       */
      setActive( model, model.get( options.observe ) );
      this.listenTo(model, 'change:' + options.observe, setActive );
    }
  },
  initialize: function() {
     ImageControl.createMediaFrame();
  },
  onRender: function() {
    var options = this.model.get('options');
    this.$('.cs-image').toggleClass( 'pattern', (options['pattern'] === true) )
  }
},{

  // Static methods

  uploader: null,

  createMediaFrame: function() {

    if ( this.uploader ==  null) {
       this.uploader = new wp.media.view.MediaFrame.Cornerstone({
        className: 'media-frame cs-media-frame',
        multiple: false,
        title: 'THAT TITLE THOUG',
        library: { type: 'image' },
        button: { text:  'Insert Image' }
      });

    }

  }

});

module.exports = ImageControl;
},{}],111:[function(require,module,exports){
module.exports = {

	// General Purpose
	'title'                   : require('./title'),
	'toggle'                  : require('./toggle'),
	'text'                    : require('./text'),
	'textarea'                : require('./textarea'),
	'editor'                  : require('./editor'),
	'code-editor'             : require('./code-editor'),
	'image'                   : require('./image'),
	'select'                  : require('./select'),
	'wpselect'                : require('./wpselect'),
	'sortable'                : require('./sortable'),
	'number'                  : require('./number'),
	'color'                   : require('./color'),
	'choose'                  : require('./choose'),
	'multi-choose'            : require('./multi-choose'),
	'icon-choose'             : require('./icon-choose'),
	'dimensions'              : require('./dimensions'),
	'date'                    : require('./date'),

	// Special
	'info-box'                : require('./info-box'),
	'custom-markup'           : require('./custom-markup'),
	'breadcrumbs'             : require('./breadcrumbs'),

	// Action Buttons
	'element-actions'         : require('./element-actions'),
	'column-actions'          : require('./column-actions'),
	'row-actions'             : require('./row-actions'),
	'section-actions'         : require('./section-actions'),
	'settings-actions'        : require('./settings-actions'),
	'layout-actions'          : require('./layout/layout-actions'),
	'template-actions'        : require('./layout/template-actions'),
	'template-select'         : require('./layout/template-select'),
	'template-remove'         : require('./layout/template-remove'),


	// Layout
	'sortable-sections'       : require('./layout/sortable-sections'),
	'sortable-rows'           : require('./layout/sortable-rows'),
	'column-layout'           : require('./layout/column-layout'),
	'column-order'            : require('./layout/column-order'),

	// Layout - Templates
	'template-save-dialog'    : require('./layout/template-save-dialog'),
	'template-upload-dialog'  : require('./layout/template-upload-dialog')
}
},{"./breadcrumbs":98,"./choose":99,"./code-editor":100,"./color":101,"./column-actions":102,"./custom-markup":104,"./date":105,"./dimensions":106,"./editor":107,"./element-actions":108,"./icon-choose":109,"./image":110,"./info-box":112,"./layout/column-layout":113,"./layout/column-order":115,"./layout/layout-actions":116,"./layout/sortable-rows":117,"./layout/sortable-sections":118,"./layout/template-actions":119,"./layout/template-remove":120,"./layout/template-save-dialog":121,"./layout/template-select":122,"./layout/template-upload-dialog":123,"./multi-choose":124,"./number":125,"./row-actions":126,"./section-actions":127,"./select":128,"./settings-actions":129,"./sortable":132,"./text":133,"./textarea":134,"./title":135,"./toggle":136,"./wpselect":137}],112:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	template: 'controls/info-box',
	controlName: 'info-box',
	onCustomVisibility: function() {
		// TODO: Info boxes should have a control option to force visibility instead
		// of hiding with help text.
		this.$el.toggleClass( 'hide', !cs.options.request( 'help:text' ) );
	}
});
},{}],113:[function(require,module,exports){
var RowValidator = require('../../../utility/row-validator');
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'column-layout',
	bindings: {
    '#column-layout': {
      observe: '_column_layout',
      events: ['blur'],
      onSet: 'formatColumnLayout',
      updateModel: 'confirmFormat',
      initialize: function($el, model, options) {

        this.$( '#column-layout' ).keyup(function (e) {
          if (e.keyCode === 13) {
            Backbone.$(this).blur();
          }
        });

        /**
         * Handler to set the active state based on the model value
         */
        setActive = _.bind( function( model, value ) {

          // Update active columns
          if ( value != 'custom' ) {
            var widths = value.split(" + ");

            model.elements.each(function(column){


              if ( _.isEmpty( widths ) ) {
                column.set( '_active', false );

                return;
              }

              column.set( '_active', true );
              var width = widths.shift()
              column.set( 'size', width );

            });

            model.elements.sort();
          }

          // Update control state
          this.$( '#column-layout' ).hide();
          this.$( 'li' ).removeClass( 'active' );
          $active = this.$( 'li[data-layout="' + value + '"]' );

          if ( $active.length ) {
            $active.addClass( 'active' );
            return;
          }

          this.$( 'li.custom' ).addClass( 'active' );
          this.$( '#column-layout' ).show();

        }, this );

        this.$('ul li').click( _.bind(function( e ) {

          $target = this.$(e.currentTarget);
          if ( $target.hasClass( 'custom' ) ) {
            setActive( model, 'custom' );
            return;
          }

          var data = $target.attr('data-layout');

          if ( RowValidator.layoutIsValid( data ) ) {
            model.set( options.observe, data );
            this.$('#column-layout').val( data );
          }
        }, this ) );

        /**
         * Set the initial active state, then listen to model changes to change the state later
         */
        setActive( model, model.get( options.observe ) );
        this.listenTo( model, 'change:' + options.observe, setActive );

        this.listenTo( model, 'position:updated', this.render );


        this.listenTo( model.elements, 'sort', function() {

          var columnWidths = [];

          model.elements.each( function( column ) {
            if ( column.get( '_active' ) ) columnWidths.push( column.get( 'size' ) );
          } );

          model.set( '_column_layout', columnWidths.join(' + ').trim() );

        } );

      }
    }
  },

  formatColumnLayout: function(value, options) {
    return RowValidator.reduceFractions( (_.map(value.split("+"),function(part){ return part.trim(); })).join(' + ') );
  },

  confirmFormat: function(value, event, options) {
    return RowValidator.layoutIsValid( this.formatColumnLayout( value ) );
  },

  textReplacements: {
		'%%title%%': function() {
    	return cs.l18n('row-numeric').replace('%s', this.proxy.getIndex() + 1 );
  	}
	}

});
},{"../../../utility/row-validator":95}],114:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
	template: 'controls/column-order-item',

	ui: {
  	'handle': 'span.handle'
  },

  events: {
    'dragula:drop': 'updatePosition',
    'mouseover': 'mouseOver',
    'mouseout': 'mouseOut'
  },

  triggers: {
    'click @ui.handle': 'click:action',
  },

  modelEvents: {
    "change:title": "render"
  },

  className: function() {
    return 'cs-column-order-item cs-' + this.model.get('size').replace('/','-');
  },

  updatePosition: function( target, source, sibling ) {
    this.triggerMethod( 'update:position', target, source, sibling );
  },

  mouseOver: function( e ) {
    this.model.trigger('observe:start');
  },

  mouseOut: function( e ) {
    this.model.trigger('observe:end');
  }

})
},{}],115:[function(require,module,exports){
var Sortable = require('../sortable');
module.exports = Sortable.extend({

  controlName: 'column-order',
  emptyView: CS.Mn.ItemView.extend({
    tagName: 'li',
    className: 'column empty',
    template: false,
  }),

  dragulaConfig: function () {
  	return {
  		offset: function( offset, e, item ) {
  			offset.x = Backbone.$(item).width() / 2; // snap to horizontal center
  			return offset;
  		},
  		direction: 'horizontal',
		  revertOnSpill: true,
  	}
  },

  getChildView: function() {
    return require( './column-order-item' );
  },

	filterBy: '_active',
  canAdd: false,

	textReplacements: {
		'%%title%%': function() {
    	return cs.l18n('row-numeric').replace('%s', this.proxy.getIndex() + 1 );
  	}
	},

  onChildviewClickAction: function( child ) {
  	cs.events.trigger( 'inspect:element', child.model );
  },

  getSortableContainer: function() {
  	return this.$('ul.cs-column-order')[0];
  },


});
},{"../sortable":132,"./column-order-item":114}],116:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-actions cs-control-divider',
  template: 'layout/actions',
  controlName: 'actions',

  ui: {
    'new': '.action.new',
    'templates' : '.action.templates',
  },

  events: {
    'click @ui.new': 'addItem',
    'click @ui.templates': 'openTemplates'
  },

  initialize: function( options ) {
    this.proxy = this.model.proxy || null;
  	this.selected = options.selected || undefined;
  },

  addItem: function() {
  	cs.events.trigger( 'add:section' );
  },

  openTemplates: function() {
    cs.navigate.trigger( 'layout:templates' );
  }

});
},{}],117:[function(require,module,exports){
var Sortable = require('../sortable');
module.exports = Sortable.extend({

	emptyView: false,
	wideControls: true,

  confirmMessage: cs.l18n('layout-row-delete-confirm'),
  actions: [
    { icon: 'copy', tooltip: cs.l18n('tooltip-copy') },
    { icon: 'trash-o', tooltip: cs.l18n('tooltip-delete') },
    { icon: 'search', tooltip: cs.l18n('tooltip-inspect') },
  ],

  customChildTitle: function( child ) {
    return cs.l18n('row-numeric').replace( '%s', this.children.length );
  },

  onChildviewClickActionAlt: function( child ) {
  	cs.events.trigger( 'inspect:element', child.model );
  },

  onChildviewClickHandle: function( item ) {
  	cs.events.trigger( 'inspect:layout', item.model );
  },

  onChildviewRender: function( child ) {

    var model = cs.navigate.request( 'layout:active:row' );

    if ( model && child.model && model.cid == child.model.cid ) {
      child.$el.addClass('active');
    }

  },

  onRemoveItem: function( model ) {
  	var active = cs.navigate.request( 'layout:active:row' );

    if ( active && model && active.cid == model.cid ) {
    	_.defer( _.bind( function(){
    		this.inspectFirst();
    	}, this ) );
    }

  },

  inspectFirst: function() {
  	cs.events.trigger( 'inspect:layout', this.collection.first() );
  },

  onResetLastItem: function() {
  	this.inspectFirst();
  }

});
},{"../sortable":132}],118:[function(require,module,exports){
var Sortable = require('../sortable');
module.exports = Sortable.extend({

	emptyView: false,

  canAdd: false,
  confirmMessage: cs.l18n('layout-row-delete-confirm'),

  onChildviewClickHandle: function( item ) {
  	cs.events.trigger( 'inspect:layout', item.model, { navigate: true } );
  	cs.navigate.trigger( 'auto:focus', 'title' );
  },

  onAfterBaseRender: function() {
    this.$el.toggleClass('hide', ( this.collection.length == 0 ));
  }

});
},{"../sortable":132}],119:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-template-actions cs-control-divider',
  template: 'layout/sub-templates/template-actions',
  controlName: 'actions',

  ui: {
    'save':    '.action.save',
    'upload' : '.action.upload',
  },

  events: {
    'click @ui.save':   'save',
    'click @ui.upload': 'upload'
  },

  initialize: function( options ) {
    cs.channel.trigger( 'block:gen' );
    this.proxy = this.model.proxy || null;
  	this.selected = options.selected || undefined;
  },

  save:function() {
    this.proxy.set('action', 'save');
  },

  upload: function() {
    this.proxy.set('action', 'upload');
  }

});
},{}],120:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
  //template: 'layout/sub-templates/template-select',
  controlName: 'template-select',
  bindingSelector: 'select',

  ui: {
    'remove' : 'button.remove',
  },

  triggers: {
    'click @ui.block': 'insert:block',
  },

  behaviors: {
    Confirm: {
      ui: 'remove',
      message: cs.l18n('templates-remove-message'),
    }
  },

  onCustomVisibility: function(){
    var opts = this.model.get('options');
    this.$el.toggleClass( 'hide', ( opts.choices && opts.choices.length < 1 ) );
  },

  onConfirmAccept: function() {
    cs.templates.trigger( 'delete', this.$('select').val() )
  },

});
},{}],121:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({

  template: 'layout/sub-templates/save-dialog',
  controlName: 'template-save-dialog',

  bindingSelector: 'input[type=text]',

  ui: {
    'download' : '.action.download',
    'save'     : '.action.save',
    'close'    : 'button.close'
  },

  events: {
    'click @ui.download' : 'download',
    'click @ui.save'     : 'save',
    'click @ui.close'    : 'close'
  },

  behaviors: {
    Confirm: {
      ui: 'save',
      message: cs.l18n( 'templates-save-message' ),
      yep:     cs.l18n( 'templates-save-yep' ),
      nope:    cs.l18n( 'templates-save-nope' )
    }
  },

  onConfirmAccept: function() {
    cs.templates.trigger( 'save', 'page', this.proxy.get( 'title' ) );
    this.close();
  },

  onConfirmDecline: function() {
    cs.templates.trigger( 'save', 'block', this.proxy.get( 'title' ) );
    this.close();
  },

  download: function() {
    cs.templates.trigger( 'download', this.proxy.get( 'title' ) );
    this.proxy.set( 'action', 'none' );
  },

  close: function() {
    this.proxy.set( 'action', 'none' );
  },

  onCustomVisibility: function( visible ) {
    if ( visible )
      this.$('input[type="text"]').focus();
  }

});
},{}],122:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	//template: 'layout/sub-templates/template-select',
  controlName: 'template-select',
	bindingSelector: 'select',

	ui: {
    'page' : 'button.page',
    'block': 'button.block',
  },

  triggers: {
    'click @ui.block': 'insert:block',
  },

  behaviors: {
    Confirm: {
    	ui: 'page',
      message: cs.l18n('templates-overwrite-message'),
      yep:     cs.l18n('templates-overwrite-yep'),
      nope:    cs.l18n('templates-overwrite-nope')
    }
  },

	onCustomVisibility: function(){
		var opts = this.model.get('options');
		this.$el.toggleClass( 'hide', ( opts.choices && opts.choices.length < 1 ) );
	},

	onConfirmAccept: function() {
		//cs.message.trigger( 'notice', 'Loading selected page template.' );

		_.defer( _.bind( function() {
			cs.templates.trigger( 'import', this.$('select').val(), 'page' )
		}, this ) );
	},

	onInsertBlock: function() {
		//cs.message.trigger( 'notice', 'Loading selected block.' );
		_.defer( _.bind( function() {
			cs.templates.trigger( 'import', this.$('select').val(), 'block' )
		}, this ) );
	},



});
},{}],123:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({

  template: 'layout/sub-templates/upload-dialog',
  controlName: 'template-upload-dialog',

  ui: {
    'upload' : 'button.process',
    'close': 'button.close',
  },

  events: {
    'click @ui.close':  'close',
    'click @ui.upload': 'confirmUpload'
  },

  initialize: function() {
    this.listenTo(cs.data, 'template:upload:complete', this.uploadComplete );
  },

  confirmUpload: function() {
    cs.confirm.trigger( 'open', {
      message: cs.l18n('templates-upload-message'),
      accept:  _.bind( this.uploadPage, this ),
      decline: _.bind( this.uploadBlock, this ),
      yep:     cs.l18n('templates-upload-yep'),
      nope:    cs.l18n('templates-upload-nope')
    });
  },

  uploadPage: function() {
    this.deferredUpload( 'page' );
  },

  uploadBlock: function() {
    this.deferredUpload( 'block' );
  },

  deferredUpload: function( format ) {
    _.defer( _.bind( this.upload, this ), format );
  },

  upload: function( format ) {

    cs.message.trigger( 'notice', 'Cornerstone is uploading your template...' );

    var file = this.$('#template-upload')[0].files[0];

    if (!file || file.name.match(/.+\.csl/)) {

      var reader = new FileReader();

      reader.onload = function(e) {
        cs.data.trigger('template:upload:complete', JSON.parse( reader.result ), format );
      }

      try {
        reader.readAsText(file);
      } catch (e) {
        cs.message.trigger( 'error', cs.l18n('templates-error-read') );
      }

    } else {
      cs.message.trigger( 'error', cs.l18n('templates-error-upload') );
      console.warn('Invalid template file');
    }

  },

  uploadComplete: function( template, format ) {
    cs.templates.trigger( 'import', template.elements, format );
    this.resetForm();
    this.close();
  },

  resetForm: function() {
    var $input = this.$('#template-upload');
    $input.replaceWith($input.clone());
  },

  close: function() {
    this.proxy.set( 'action', 'none' );
  }

});
},{}],124:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'multi-choose',
  controlTemplate: 'controls/choose',
	binding: {
	  initialize: function($el, model, options) {

    	/**
    	 * Update Model when a new option is clicked
    	 */
      this.$('li').on('click', _.bind( function (e) {
      	var selected = _.clone( model.get( options.observe ) );
        var choice = this.$(e.currentTarget).data('choice')

        // Make sure we're working with an array
        if ( !_.isArray(selected) )
          selected = [];

        // Add or remove
        if ( _.contains(selected, choice ) ) {
          selected = _.without(selected, choice)
        } else {
          selected.push(choice);
        }

        model.set( options.observe, selected );
        model.trigger('change:' + options.observe, model, selected, {} );
      }, this ) );

      /**
       * Handler to set the active state based on the model value
       */
    	var setActive = _.bind( function( model, value ) {
        this.$('li').removeClass('active').siblings( _.reduce( value || [], function( memo, item) {
          return memo + ',[data-choice=' + item + ']';
        },'.always-on') ).addClass('active');
    	}, this );

    	/**
    	 * Set the initial active state, then listen to model changes to change the state later
    	 */
    	setActive( model, model.get( options.observe ) );
    	this.listenTo(model, 'change:' + options.observe, setActive );


    }
	},
  onBeforeRender: function() {

    /**
     * Make sure we have a valid number of columns.
     */
    var options = this.model.get('options');
    if ( !_.contains(['2', '3', '4', '5'], options.columns ) ) {
      options.columns = '2';
      this.model.set('options',options);
    }
  }
});
},{}],125:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'number',
	bindingSelector: 'input[type=number]'
});
},{}],126:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
	className: 'cs-control cs-control-actions cs-control-divider',
	template: 'inspector/row-actions',
	controlName: 'actions',
	ui: {
		'layout': '.action.manage-layout',
		'delete': '.action.delete'
	},

	events: {
		'click @ui.delete': 'elDelete',
		'click @ui.layout': 'layout'
	},

	initialize: function( options ) {
		this.proxy = this.model.proxy || null;
		this.selected = options.selected || undefined;
	},

	layout: function() {
		cs.events.trigger( 'inspect:layout', this.proxy.getSource(), { navigate: true } );
	},

	elDelete: function() {
		cs.global.trigger( 'element:delete', this.proxy.getSource() );
	},

});
},{}],127:[function(require,module,exports){
arguments[4][126][0].apply(exports,arguments)
},{"dup":126}],128:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'select',
	bindingSelector: 'select',
	controlEvents: {
		'change select': 'notLiveTrigger'
	},
	onAfterBaseRender: function() {
		var localOpts = this.model.get('options');
		_.each( localOpts.choices, function( item ) {

			if (item.disabled)
				this.$('option[value="' + item.value + '"]').prop( 'disabled', true );

		}, this );
	}
});
},{}],129:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	tagName: 'li',
  className: 'cs-control cs-control-actions cs-control-divider',
  template: 'settings/actions',
  controlName: 'actions',
	ui: {
    'triggerCSS': '.action.css',
    'triggerJS' : '.action.js',
  },

  events: {
    'click @ui.triggerJS': 'triggerJS',
    'click @ui.triggerCSS': 'triggerCSS'
  },

  initialize: function( options ) {
    this.proxy = this.model.proxy || null;
  	this.selected = options.selected || undefined;
    this.jsMessage = _.once( function(){
      //cs.message.trigger( 'notice', cs.l18n( 'settings-js-message' ) );
    } )
  },

  triggerCSS: function() {
    cs.navigate.trigger( 'open:code:editor', 'custom_css' );
  },

  triggerJS: function() {
    if ( !cs.config('unfilteredHTML') ) {
      cs.message.trigger( 'notice', cs.l18n( 'settings-js-denied' ), 8000 );
      return;
    }
    cs.navigate.trigger( 'open:code:editor', 'custom_js' );
    this.jsMessage();
  },

});
},{}],130:[function(require,module,exports){
var SortableItem = require('./sortable-item');

module.exports = SortableItem.extend({

  className: 'sortable-item wide-controls',
  template: 'controls/sortable-item-wide',

  ui: {
    'action' : 'button.action1',
    'confirm': 'button.action2',
    'actionAlt' : 'button.action3',
    'handle': 'span.handle'
  },

  triggers: {
    'click @ui.action': 'clickAction',
    'click @ui.actionAlt': 'clickActionAlt',
    'click @ui.handle': 'click:handle',
  },

});
},{"./sortable-item":131}],131:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({

  tagName: 'li',
  className: 'sortable-item',
  template: 'controls/sortable-item',

  ui: {
    'action' : 'button.action1',
    'confirm': 'button.action2',
    'handle': 'span.handle'
  },

  events: {
    'dragula:drop': 'updatePosition',
    'mouseover': 'mouseOver',
    'mouseout': 'mouseOut',
  },

  triggers: {
    'click @ui.action': 'clickAction',
    'click @ui.handle': 'click:handle',
  },

  modelEvents: {
    "change:title": "render"
  },

  behaviors: {
    Confirm: {
      message: function(){ return ( this.atFloor() ) ? cs.l18n('sortable-at-floor') : this.confirmMessage; },
      yep:     function(){ return cs.l18n('confirm-yep'); },
      nope:    function(){ return ( this.atFloor() ) ? cs.l18n('confirm-back') : cs.l18n('confirm-nope'); }
    },
    ConfirmWarn: {
      message: function(){ return cs.l18n('sortable-at-cap'); },
      yep:     function(){ return ''; },
      nope:    function(){ return cs.l18n('confirm-back'); }
    }
  },
  canQuickConfirm: true,

  updatePosition: function( target, source, sibling ) {
    this.triggerMethod( 'update:position', target, source, sibling );
  },

  serializeData: function( ) {

    var data = _.extend( CS.Mn.ItemView.prototype.serializeData.apply(this,arguments), {
      actions: this.actions
    });

    if ( _.isFunction( this.customTitle ) ) {
      data.title = this.customTitle( this );
    } else {
    	data.title = this.model.get( this.title_field );
    }

    return data;
  },

  mouseOver: function( e ) {
    this.model.trigger('observe:start');
  },

  mouseOut: function( e ) {
    this.model.trigger('observe:end');
  },

});
},{}],132:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'sortable',
	childViewContainer: 'ul',

  actions: [
    { icon: 'copy', tooltip: cs.l18n('tooltip-copy') },
    { icon: 'trash-o', tooltip: cs.l18n('tooltip-delete') },
  ],

  wideControls: false,

  getChildView: function() {
    return ( this.wideControls ) ? require('./sortable-item-wide') : require('./sortable-item');
  },

  confirmMessage: cs.l18n('sortable-remove'),

  dragulaConfig: function () {
  	return {
  		offset: function( offset, e, item ) {
  			offset.y = Backbone.$(item).height() / 2; // snap to vertical center
  			return offset;
  		},
		  revertOnSpill: true,
  	}
  },

  buildChildView: function(child, ChildViewClass, childViewOptions) {

    var view = new ChildViewClass(_.extend({model: child}, childViewOptions));

    view.atFloor = _.bind(function(){
      return ( this.floor >= this.collection.length );
    }, this);

    view.atCap = _.bind( function(){
      return ( !_.isNull( this.capacity ) && this.capacity <= this.collection.length );
    }, this);

    if ( _.isFunction( this.customChildTitle) ) {
      view.customTitle = _.bind( this.customChildTitle, this );
    }

    view.title_field = this.title_field;
    view.actions = this.actions;
    view.confirmMessage = this.confirmMessage;
    return view;
  },

	emptyView: CS.Mn.ItemView.extend({
  	tagName: 'li',
  	className: 'sortable-item empty',
  	template: 'controls/sortable-empty',
    events: {
      'click span.handle': 'click'
    },
    click: function( e ) {
      this.triggerMethod( 'empty:click:add', e );
    }
	}),
	sort: false,

  filterBy: false,
  canAdd: true,
  canCompact: false,

	ui: {
    'add': 'button.cs-add-sortable-item',
  },

  events: {
    'click @ui.add': 'addItem',
  },

  initialize: function() {

  	this.drake = Cornerstone.Vendor.dragula( _.result( this,'dragulaConfig') );

		this.drake.on('drag', function( el ) {
			cs.events.trigger( 'preview:no-pointer', true );
		});

		this.drake.on('dragend', function( el ) {
			cs.events.trigger( 'preview:no-pointer', false );
		});

		this.drake.on('drop', function( el, target, source, sibling ) {
			Backbone.$(el).trigger( 'dragula:drop', [ target, source, sibling ] );
		});

  },

  controlData: function() {

    var data =  { canAdd: this.canAdd };

    if ( !_.isNull( this.capacity ) && this.collection.length >= this.capacity )
      data.canAdd = false;

    data.empty = ( this.collection.length == 0 )

    return data;
  },

  onChildviewEmptyClickAdd: function( e ) {
    this.addItem();
  },

  onChildviewUpdatePosition: function( child, target, source, sibling ) {

  	// Wait until Dragula removes the mirror image.
  	_.defer( _.bind( function(){
  		this.triggerMethod( 'item:before:position:updated', child );
    	this.collection.trigger( 'update:position', child.model, child.$el.index() );
    	this.triggerMethod( 'item:position:updated', child );
  	}, this ) );

  },

  filter: function (child, index, collection) {
    if (!this.filterBy) return true;

    return ( child.get( this.filterBy ) );
  },


  onProxyReady: function() {

    this.collection = this.proxy.elements;

    this.listenTo( this.collection, 'reset', this.render );
    this.listenTo( this.collection, 'sort', this.render );
    this.listenTo( this.collection, 'remove', this.render );
    this.listenTo( this.collection, 'add', this.render );

    var options = this.model.get('options')

    if (options.type) {
      this.collection.childType = options.type;
    }

    this.floor = ( options.floor || 0 );
    this.title_field = ( options.title_field || 'title' );
    this.capacity = (options.capacity) ? options.capacity : null;

  },

  // Default first action is copy
  onChildviewClickAction: function( item ) {
    if ( item.atCap() ) {
      item.trigger('confirm:warn:open');
    } else {
    	cs.elements.trigger( 'duplicate', item.model );
    }
  },

  // Default handle is to sub-inspect
  onChildviewClickHandle: function( item ) {
  	cs.events.trigger( 'inspect:element', item.model, false ); // manage navigation from here
    var pane = ( cs.navigate.request('active:pane') == 'settings' ) ? 'settings' : 'inspector';
    cs.navigate.trigger( pane + ':item' );
  },

  // Default confirmation is to delete
  onChildviewConfirmAccept: function( item ) {
  	var atFloor = item.atFloor()

  	this.triggerMethod( 'remove:item', item.model );
  	cs.elements.trigger( 'delete', { model: item.model } );

  	if ( atFloor ) {
  		this.addItem();
  		this.triggerMethod( 'reset:last:item' );
  	}
  },

  getSortableContainer: function() {
  	return this.$('ul.cs-sortable')[0];
  },

	onRender: function() {
		this.drake.containers = [];
    this.drake.containers.push( this.getSortableContainer() );
    this.triggerMethod('after:render');
  },

  addItem:function() {
  	var options = this.model.get('options');
    var title = options.newTitle || cs.l18n('sortable-default');
    cs.elements.trigger( 'add:item', options.element, this.proxy, title, options.title_field );
  },

  onDestroy: function() {
  	this.drake.destroy();
  }

});
},{"./sortable-item":131,"./sortable-item-wide":130}],133:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'text',
	bindingSelector: 'input[type=text]',
	controlEvents: {
		'blur input': 'notLiveTrigger'
	},
	onRender: function() {
		var options = this.model.get('options');
		if ( options.placeholder )
			this.$('input[type=text]').attr('placeholder', options.placeholder );
	}
});
},{}],134:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'textarea',
	bindingSelector: 'textarea',
	htmlhint: { 'tagname-lowercase': false, 'attr-lowercase': false, 'attr-value-double-quotes': false, 'doctype-first': false, 'tag-pair': true, 'spec-char-escape': true, 'id-unique': false, 'src-not-empty': false, 'attr-no-duplication': false, 'title-require': false },
  onProxyReady: function() {
    var opts = this.model.get('options');
    if (!opts.expandable && opts.expandable !== false) {
      opts.expandable = opts.controlTitle || true;
      this.model.set('options', opts);
    }

  },
  onRender: function() {
    var options = this.model.get('options');
    if ( options.placeholder )
      this.$('textarea').attr('placeholder', options.placeholder );

    if ( options.htmlhint ) {
    	var $textarea = this.$('textarea');
    	$textarea.on( 'blur', _.bind( function(){

    		var errors = _.map( Cornerstone.Vendor.HTMLHint.verify( $textarea.val(), this.htmlhint ), function( item ) {
    			return item.rule.id;
    		});
    		if ( !_.isEmpty( errors ) ) {
    			cs.message.trigger( 'error', CS.Mn.Renderer.render( 'utility/htmlhint', { errors: errors } ), 7000 );
    		}
    	}, this ) );
    }
  },

  onExpandedOpen: function() {

  }

});
},{}],135:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'title',
	divider: true,
	canCompact: false,
	bindingSelector: 'input[type=text]',

	ui: {
    'inspect': 'button.cs-title-button',
  },

  triggers: {
    'click @ui.inspect': 'inspect',
  },

	onInspect: function() {
		cs.events.trigger( 'inspect:element', this.proxy.getSource() );
  }

});
},{}],136:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'toggle',
  binding: {
    initialize: function($el, model, options) {
      /**
       * Update Model when a new option is clicked
       */
      this.$('ul.cs-toggle').on('click', _.bind( function (e) {
        model.set( options.observe, this.$(e.currentTarget).hasClass('off') );
        this.notLiveTrigger();
      }, this ) );

      /**
       * Handler to set the active state based on the model value
       */
      var setActive = _.bind( function( model, state ) {
        var state = state || this.model.get( 'default' ) || false;
        this.$('.cs-toggle').toggleClass( 'on', state ).toggleClass( 'off', !state )
      }, this );

      /**
       * Set the initial active state, then listen to model changes to change the state later
       */
      setActive( model, model.get( options.observe ) );
      this.listenTo(model, 'change:' + options.observe, setActive );

    }
  }
})
},{}],137:[function(require,module,exports){
module.exports =  Cornerstone.ControlViews.Base.extend({
	controlName: 'wpselect',
	bindingSelector: 'select',
	controlEvents: {
		'change select': 'notLiveTrigger'
	},
	onBeforeBaseRender: function() {
		var options = this.model.get('options');
		this.$select = Backbone.$( options.markup || '<select class="empty"></select>' );
		this.wpDefault = this.$select.val();
		this.$( '.cs-wp-select' ).append( this.$select );
	}
});
},{}],138:[function(require,module,exports){
// Confirm
module.exports = CS.Mn.ItemView.extend({
	className: 'cs-confirm',
	template: 'extra/confirm',

	defaultConfig: {
		message: cs.l18n( 'confirm-message' ),
		allowQuickConfirm: false,
		subtext: false,
		yep: cs.l18n( 'confirm-yep' ),
		nope: cs.l18n( 'confirm-nope' ),
		classes: [],
		view: null
	},

	events: {
		'click .yep':  'acceptDebounce',
		'click .nope': 'declineDebounce'
	},

	initialize: function( options ) {

		this.data = _.clone( this.defaultConfig );

		// Prevent multiple clicks
		this.acceptDebounced = _.debounce( _.bind( this.accept, this ), 500, true );
		this.declineDebounced = _.debounce( _.bind( this.decline, this ), 500, true );

		this.listenTo( cs.confirm, 'abort', this.declineDebounce );
		this.listenTo( cs.confirm, 'open', this.open );
	},

  acceptDebounce: function() {
    this.acceptDebounced();
  },

  declineDebounce: function() {
    this.declineDebounced();
  },

	open: function( data, context ) {
		this.context = context || {};
		this.data = _.extend( _.clone( this.defaultConfig ), data );
		if ( this.data.allowQuickConfirm && cs.data.request( 'delete:confirm:key' ) ) {
			this.accept();
			return;
		}
		this.render();
		this.$el.addClass( 'active' );
  },

  serializeData: function() {

    var data = _.clone( this.data );

    if ( _.isFunction( data.message ) ) data.message = data.message.call( this.context );
    if ( _.isFunction( data.subtext ) ) data.subtext = data.subtext.call( this.context );
    if ( _.isFunction( data.classes ) ) data.classes = data.classes.call( this.context );
    if ( _.isFunction( data.yep ) )         data.yep = data.yep.call( this.context );
    if ( _.isFunction( data.nope ) )       data.nope = data.nope.call( this.context );

    data.classes.unshift( 'cs-confirm-content' );
    data.contentClass = data.classes.join( ' ' );
    return data;
  },

	accept: function() {

		if ( _.isFunction( this.data.accept ) ) {
			this.data.accept();
		}

		this.close();

	},

	decline: function() {

		if ( _.isFunction( this.data.decline ) ) {
			this.data.decline();
		}

		this.close();

	},

	close: function() {
		this.context = null;
		this.data = {};
		this.$el.removeClass( 'active' );
	}

} );

},{}],139:[function(require,module,exports){
// Expand
module.exports = CS.Mn.ItemView.extend({
  tagName: 'button',
  className: 'expand cs-icon',
  template: false,
  attributes: { 'data-cs-icon': cs.fontIcon('play-circle') },
  events: { 'click': 'collapse' },

  initialize: function() {

    Backbone.$('body').toggleClass('cs-editor-active', true ).toggleClass('cs-editor-inactive', false );

    this.listenTo(cs.extra, 'flyout:collapse', function() {
      Backbone.$('body').toggleClass('cs-editor-active', false ).toggleClass('cs-editor-inactive', true );

      cs.extra.trigger( 'set:collapse', true );
    } );

    // Event propogation
    this.listenTo( cs.extra, 'set:collapse', function( state ){
      cs.extra.reply( 'get:collapse', state );
       cs.global.trigger( 'set:collapse', state );
    });

  },

  collapse: function( state ) {

  	cs.extra.trigger( 'set:collapse', false );
    Backbone.$('body').toggleClass('cs-editor-active', true ).toggleClass('cs-editor-inactive', false );
    cs.extra.trigger( 'flyout', 'collapse' );

  }
});
},{}],140:[function(require,module,exports){
// Expansion
var ControlListView = require('../controls/control-collection')

module.exports = CS.Mn.ItemView.extend({
  className: 'cs-expanded-content-outer',
  template: 'extra/expanded-control',

  events: {
    'click .cs-expanded-close': 'shutdown',
    'keyup': 'escape'
  },

  initialize: function( options ) {
    this.listenTo( cs.extra, 'set:collapse', this.collape );
    this.linkedView = null;
    this.listenTo( cs.events, 'expand:control:open', this.open );
  },

  onRender: function() {
  	this.controlView = new ControlListView( { collection: cs.component('inspector').getExpansionControls() } );
    this.controlView.render();
    this.$('.cs-expanded-content-inner').append(this.controlView.$el);
  },

  open: function() {

		this.controlView.render();

		_.defer( _.bind ( function(){

			this.$el.addClass('active');

			this.controlView.children.each(function(view){
				view.triggerMethod( 'expanded:open' );
			} );

    	var $textarea = this.$('textarea');
    	console.log( $textarea );
    	$textarea.height( this.$el.height() * .60 );
    	_.delay( function(){
	    	$textarea.focusEnd();
	    }, 100 );
    }, this ) );

  },

  escape: function( e ) {
    if (e.keyCode === 27) {
      this.shutdown();
    }
  },

  collape: function( state ) {
    if (state) {
      this.shutdown();
    }
  },

  shutdown: function() {

    // Animate out
    this.$el.removeClass('active');

    _.delay(function(){
    	cs.events.trigger( 'expand:close' );
    }, 200 );

  }

});
},{"../controls/control-collection":103}],141:[function(require,module,exports){
// Home
module.exports = CS.Mn.ItemView.extend({
  className: 'cs-home',
  template: 'extra/home',

  initialize: function() {
    this.listenTo( cs.channel, 'save:complete', this.render );
    this.listenTo( cs.channel, 'update:saved:last', this.render );
  },

  serializeData: function() {
    var savedLast = cs.data.request( 'saved:last' );
    var minAgo = 2;
    var data = {
      savedLastMessage: ( _.isNull( savedLast ) ) ? cs.l18n('home-unsaved') : cs.l18n('home-saved-last').replace('%s', savedLast.fromNow() ),
      savedLastClass: ( _.isNull( savedLast ) || savedLast.isBefore(new Date((new Date()).getTime() - minAgo*60000)) ) ? 'warn' : 'happy',
      dashboardEditUrl: cs.config('dashboardEditUrl'),
      frontEndUrl: cs.config('frontEndUrl')
    }
    return data;
  }
});
},{}],142:[function(require,module,exports){
// Options

module.exports = CS.Mn.CompositeView.extend({
  className: 'cs-options',
  getChildView: function( item ) { return cs.controlLookup( item.get('type') ); },
  template: _.template('<div id="options-controls"><ul class="cs-controls"></ul></div>'),//'extra/options',
  childViewContainer: 'ul.cs-controls',

  initialize: function() {
  	this.collection = cs.component('options').inspect.controls;
  }

});
},{}],143:[function(require,module,exports){
// Respond
module.exports = CS.Mn.ItemView.extend({
  className: 'cs-respond',
  template: 'extra/respond',

  events: {
    'click button': 'handleClick'
  },

  initialize: function() {
    cs.extra.reply( 'width', 'xl');
    this.listenTo(cs.extra, 'respond:width', this.setRespond );
  },

  handleClick: function( e ) {

    this.$('button').removeClass('active');
    this.$('.cs-respond-labels div').removeClass('active');

    this.$(e.currentTarget).addClass('active');

    var data = this.$(e.currentTarget).data('respond');
    cs.extra.reply('width', data );
    this.$('.cs-respond-labels div[data-respond="' + data + '"]').addClass('active');
    cs.extra.trigger( 'respond:width', data );

  },

  onRender: function() {

    var width = cs.extra.request( 'width' );

    this.$('button[data-respond="' + width + '"]').addClass('active');
    this.$('.cs-respond-labels [data-respond="' + width + '"]').addClass('active');

  },

  setRespond: function( width ) {
  	var $preview = Backbone.$('.cs-preview');
    $preview.removeClass('cs-respond-xl cs-respond-lg cs-respond-md cs-respond-sm cs-respond-xs');
    if ( width ) $preview.addClass( 'cs-respond-' + width );
  }

});
},{}],144:[function(require,module,exports){
// SaveComplete
module.exports = CS.Mn.ItemView.extend({
  className: 'cs-saved',
  template: 'extra/save-complete',
  messages: cs.l18n('save-complete-messages'),

  serializeData: function() {
    return {
      message: this.messages[Math.floor(Math.random() * this.messages.length)]
    }
  },

  onRender: function() {
    this.$el.css({display : 'none', opacity : 1}).removeClass('saved-out');
  },

  onSaveComplete: function() {

    if ( !cs.config('visualEnhancements') ) {
      cs.message.trigger( 'success', cs.l18n( 'save-complete-simple' ), 1250 );
      return;
    }

    this.$el.css({display : 'table'}).addClass('saved-in');

    setTimeout( _.bind( function() {
      this.$el.animate({opacity : 0}, 650, 'linear', _.bind( function() {
        this.$el.css({display : 'none', opacity : 1}).removeClass('saved-out');
      }, this ) ).removeClass('saved-in').addClass('saved-out');
      setTimeout( _.bind( function() {
        this.render();
      }, this ), 1000);
    }, this ), 1000);

  },

});

},{}],145:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
  tagName: 'li',
  className: 'cs-control-empty',
  template: 'inspector/blank-state',
});
},{}],146:[function(require,module,exports){
// InspectorPane
var ControlListView = require('../controls/control-collection');
var ViewBasePane = require('../main/base-pane');
module.exports = ViewBasePane.extend({

	name: 'inspector',

  initialize: function() {
    this.listenTo(cs.navigate, 'refresh:inspector:heading', this.updateHeading );
  },

  onShowContent: function(){

  	this.Content.show( new ControlListView( {
  		collection: cs.component('inspector').getPrimaryControls(),
  		emptyView: require('./empty-controls')
  	}));

  },

  updateHeading: function() {
    this.$('h2').text( cs.navigate.request('inspector:heading') );
  },

  onOpenSubItem: function() {

  	this.Sub.show( new ControlListView( {
  		collection: cs.component('inspector').getSecondaryControls(),
  		emptyView: require('./empty-controls')
  	}));

  },

  updateSerializeData: function( data ) {
    var heading = cs.navigate.request('inspector:heading');
    if ( heading )
    	data.heading = heading;
    return data;
  }

});
},{"../controls/control-collection":103,"../main/base-pane":153,"./empty-controls":145}],147:[function(require,module,exports){
var ControlListView = require('../controls/control-collection')
var ViewBasePane = require('../main/base-pane');
module.exports = ViewBasePane.extend({

  name: 'layout',

  onShowContent: function(){
    this.Content.show( new ControlListView( { collection: cs.component('layout').inspect.primary.controls } ) );
  },

  onOpenSubRows: function() {
  	var ManageRowsView = require('./sub-row/layout-sub-rows');
    this.Sub.show( new ControlListView( { collection: cs.component('layout').inspect.secondary.controls } ));
  },

  onOpenSubTemplates: function() {
  	var TemplatesView = require('./sub-templates/layout-sub-templates');
    this.Sub.show( new TemplatesView() );
  },

});
},{"../controls/control-collection":103,"../main/base-pane":153,"./sub-row/layout-sub-rows":148,"./sub-templates/layout-sub-templates":149}],148:[function(require,module,exports){
// RowSubPane
var ViewControlCollection = require('../../controls/control-collection')

module.exports = CS.Mn.LayoutView.extend({

  template: 'layout/sub-row/layout-sub-row',
  className: 'cs-pane-content-inner row',
  regions: {
    Controls: '#layout-row-controls',
    //ColumnControls: '#layout-column-controls'
  },

  initialize: function() {


    // this.columnControls = new ControlCollection();
    // this.rowControls = new ControlCollection([], { proxy: this.model } );

    // this.rowControls.add({
    //   name: 'info',
    //   controlType: 'info-box',
    //   controlTitle: cs.l18n('columns-info-title'),
    //   controlTooltip: cs.l18n('columns-info-description')
    // });

    // this.rowControls.add({
    //   name: 'title',
    //   controlType: 'title',
    //   showInspectButton: true,
    //   divider: true
    // });

    // this.rowControls.add({
    //   name: 'elements',
    //   controlType: 'sortable-rows',
    //   options: {
    //     newTitle: 'Row %s',
    //     floor: 1
    //   },
    //   divider: true
    // });

    // this.listenTo( cs.navigate, 'layout:column', this.setActiveRow );

  },

  setActiveRow: function( view ) {

    // if ( view === false ) {

    //   view = {
    //     model: cs.navigate.request( 'layout:active:row' )
    //   };
    // }


    // if ( !view || !view.model ) {
    //   view = {
    //     model: this.model.get('elements').first()
    //   }
    // }

    // if (!view.model.collection || view.model.collection.length == 0) {
    //   this.ColumnControls.empty();
    //   return;
    // }

    // this.columnControls.setProxy( view.model );
    // _.invoke( _.clone( this.columnControls.models ), 'destroy' );


    // var title = cs.l18n('row-numeric').replace('%s', view.model.collection.indexOf( view.model ) + 1 );
    // this.columnControls.add({
    //   name: 'columnLayout',
    //   controlType: 'column-layout',
    //   controlTitle: cs.l18n('columns-layout-label').replace('%s', title ),
    //   controlTooltip: cs.l18n('columns-layout-tooltip'),
    //   defaultValue: '',
    // });

    // this.columnControls.add({
    //   name: 'columnOrder',
    //   controlType: 'column-order',
    //   controlTitle: cs.l18n('columns-order-label').replace('%s', title ),
    //   controlTooltip: cs.l18n('columns-order-tooltip'),
    //   defaultValue: '',
    //   divider: true
    // });

    // if ( view.$el ) {
    //   this.$('ul li.sortable-item').removeClass('active');
    //   view.$el.addClass('active');
    // }

    // cs.navigate.reply( 'layout:active:row', view.model );
    // this.ColumnControls.show( new ViewControlCollection( { collection: this.columnControls } ) );

  },

  onBeforeShow: function() {
    //this.setActiveRow( cs.navigate.request( 'layout:active:row' ) );
    //this.Controls.show( new ViewControlCollection( { collection: this.rowControls, autoFocus: 'title' } ) );
  },

});
},{"../../controls/control-collection":103}],149:[function(require,module,exports){
// TemplatesSubPane
var ViewControlCollection = require('../../controls/control-collection')

module.exports = CS.Mn.LayoutView.extend({
	template: 'layout/sub-templates/layout-sub-template',
	className: 'cs-pane-content-inner templates',

  regions: {
    Controls: '#layout-template-controls',
  },

  initialize: function() {
  	this.listenTo( cs.templates, 'control:reset', function(){
  		this.onBeforeShow();
  	});
  },

  onBeforeShow: function() {
    this.Controls.show( new ViewControlCollection( { collection: cs.component('layout-templates').controls } ) );
  },

});
},{"../../controls/control-collection":103}],150:[function(require,module,exports){
var ViewBasePane = require( '../main/base-pane' );
module.exports = ViewBasePane.extend({
	name: 'elements',
	paneTemplate: 'library/search',
	paneEvents: {
		'keyup #elements-search': 'search',
		'search #elements-search': 'search'
	},

	onShowContent: function() {
		var ViewElementLibrary = require( './library-list' );
		cs.search.reply( 'elements', '' );
		this.Content.show( new ViewElementLibrary( { collection: cs.elementLibrary.get( 'builder' ) } ) );
	},

	search: function() {
		var results = cs.elementLibrary.search( 'builder', this.$( '#elements-search' ).val().toLowerCase().trim() );
		cs.search.reply( 'elements', results );
		cs.search.trigger( 'elements' );
		this.$( '.cs-pane-content-inner' ).perfectScrollbar( 'update' );
	},

	onNavigate: function() {
		this.$( '#elements-search' ).focus();
	}

} );

},{"../main/base-pane":153,"./library-list":152}],151:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({

	className: 'cs-element-stub',

	template: 'library/element-stub',

	attributes: function() {
		var atts = {};

		if ( ! cs.options.request( 'skeleton:mode' ) && 'function' !== typeof MouseEvent   )
			atts.draggable = 'true';

		return atts;

  },

  initialize: function() {
		this.listenTo( cs.events, 'toggle:skeleton:mode', function() {
			this.$el.attr( _.extend({}, _.result( this, 'attributes' ) ) );
		} );

		// Cancel element pane drags when skeleton is inactive
    this.listenTo( cs.global, 'drag:exit', function() {
			if ( this.clone ) {
				Backbone.$( this.clone ).hide();
				Backbone.$( '#preview' ).one( 'mouseleave', _.bind( function() {
					cs.global.trigger( 'drag:resume' );
				}, this ) );
			}
		});

		this.listenTo( cs.global, 'drag:resume', function() {
			if ( this.clone ) Backbone.$( this.clone ).show();
		});

  },

  serializeData: function() {
		return _.extend( CS.Mn.ItemView.prototype.serializeData.apply( this, arguments ), {
			icon: cs.icon( this.model.get( 'icon' ) )
		});
	},

	events: {
		'dragstart.h5s':  'setData',
		'dragend.h5s':    'endDrag',
		'dragula:start':  'dragStart',
		'dragula:cloned': 'watchClone'
	},

	watchClone: function( e, clone ) {

		Backbone.$( clone ).on( 'dragula:dragend', function() {
			cs.global.trigger( 'incoming:element:end' );
		});

	},

	endDrag: function( e ) {
		cs.global.trigger( 'dragging', false );
	},

	dragStart: function( e, clone ) {
		this.clone = clone;
		cs.global.trigger( 'incoming:element', this.model.get( 'name' ) );
	},

	setData: function( e ) {

		cs.global.trigger( 'dragging', true, true );
		cs.global.trigger( 'incoming:element', this.model.get( 'name' ), true );

		var dataTransfer = e.originalEvent.dataTransfer; // No data transfer?
		dataTransfer.effectAllowed = 'copy';
		dataTransfer.dropEffect = 'copy';

		var data = JSON.stringify({
			action: 'create',
			_type: this.model.get( 'name' )
		});

		var $icon = this.$( 'svg' );

		if ( $icon.length && 'function' === typeof dataTransfer.setDragImage ) {
			dataTransfer.setDragImage( $icon[0], 25, 25 );
		}

		dataTransfer.setData( 'text', data );

	}

} );

},{}],152:[function(require,module,exports){
module.exports = CS.Mn.CollectionView.extend({
	className: 'cs-elements',
	childView: require('./element-stub'),
	childViewContainer: '.cs-pane-section ul',

	initialize: function( ) {
		this.listenTo( cs.search, 'elements', this.render );
	},

	onBeforeRender: function() {
		this.searchResults = cs.search.request( 'elements' ) || '';
	},

	viewComparator: function( a ) {
		var results = ( _.isArray( this.searchResults ) ) ? this.searchResults : [];
		return _.indexOf( results, a.get('name') );
	},

	filter: function ( child, index, collection ) {

		// Show all when not searching
		if ( !this.searchResults || '' == this.searchResults ) return true;

		// Show items that match a search query
    return _.contains( this.searchResults, child.get('name') )
  }

});
},{"./element-stub":151}],153:[function(require,module,exports){
// BasePane
var BasePane = CS.Mn.LayoutView.extend({

	name: 'undefined',

	className: function() { return 'cs-pane ' + this.name; },

	template: 'main/pane',
	paneTemplate: false,

	regions: {
    Content: '#content',
    Sub: '#sub'
  },

	events: function() {
		return _.extend( { 'click button.cs-builder-sub-back': 'back' }, this.paneEvents );
	},


  paneEvents: {},

  onBaseOpenSub: function( sub ) {

		this.Sub.empty();
		cs.navigate.trigger( 'subpane:opened', this.name + ':' + sub );
  	this.triggerMethod( 'open:sub' );
  	this.triggerMethod( 'open:sub:' + sub );

  	this.$('.cs-builder-sub').addClass('active').find('.cs-pane-content-inner').perfectScrollbar({
      suppressScrollX     : true,
      scrollYMarginOffset : 25
    });

  },

  onBaseCloseSub: function() {

  	this.$('.cs-builder-sub').removeClass('active');

  	// leave ghost markup until next open
		if (this.Sub) {
    	this.Sub.show( new Cornerstone.Mn.ItemView({
    		template: _.template( this.$('#sub').html() )
    	}) );
		}

  	this.triggerMethod( 'close:sub' );
  },

  back: function() {
  	cs.navigate.trigger( this.name + ':home' );
  },

  onBeforeShow: function(){

    this.Sub.empty();
    this.Content.empty();
    this.triggerMethod( 'show:content' );

  },

  serializeData: function() {

  	var data = _.extend( CS.Mn.LayoutView.prototype.serializeData.apply( this, arguments ), {
      heading: cs.l18n( this.name + '-heading' ),
      returnButtonText: cs.l18n( this.name + '-return' ),
      paneTemplate: this.paneTemplate,
      name: this.name
    });

  	if ( _.isFunction( this.updateSerializeData ) )
  		return this.updateSerializeData( data );

  	return data;

  }

});

module.exports = BasePane;

},{}],154:[function(require,module,exports){
// Editor

var ViewHeader    = require('./header')
  , ViewFooter    = require('./footer')
  , ViewExpansion = require('../extra/expansion')
  , ViewSkeleton  = require('./skeleton');

module.exports = CS.Mn.LayoutView.extend({

  template: 'main/editor',

  regions: {
    Header: '#header',
    Pane:   '#pane',
    Footer: '#footer',
    Expansion: '#expand',
    Skeleton: '#skeleton',
  },

  panes: {
    'layout': require('../layout/layout'),
    'elements': require('../library/element-library'),
    'inspector': require('../inspector/inspector'),
    'settings': require('../settings/settings'),
  },

  initialize: function() {

    this.listenTo( cs.navigate, 'pane', this.changePane );
    this.listenTo( cs.navigate, 'scrollbar:update', this.scrollbarUpdate );
    this.listenTo( cs.options, 'editor:position', this.setEditorPosition );



    this.listenTo( cs.message, 'notice', this.growlNotice );
    this.listenTo( cs.message, 'success', this.growlSuccess );
    this.listenTo( cs.message, 'error', this.growlError );

    this.listenTo( cs.data, 'control:not:live', this.controlNotLive );
    this.sentControlMessages = [];

    Backbone.$('#cornerstone').on('mouseenter mouseleave', '.cs-sortable li.sortable-item', _.bind( this.sortableHover, this ) );

    if ( localStorage['CornerstonePane'] == 'settings' ) {
      cs.data.reply('saved:last', Cornerstone.Vendor.moment() );
      cs.channel.trigger('update:saved:last');
    }

    this.listenTo( cs.events, 'preview:no-pointer', this.togglePreviewPointerEvents );
    this.listenTo( cs.events, 'skeleton:dragging', this.toggleDragging );

    this.keybindingToggleClasses();

  },

  onRender: function() {

    var prevPane = localStorage['CornerstonePane'];
    localStorage['CornerstonePane'] = false;

    cs.data.reply('scrollbar:width', this.getScrollbarWidth() );

    this.changePane( _.has( this.panes, prevPane ) ? prevPane : 'layout' );
    this.Header.show( new ViewHeader() );
    this.Footer.show( new ViewFooter() );
    this.Expansion.show( new ViewExpansion() );
    this.Skeleton.show( new ViewSkeleton( { model: cs.post.data } ) );

    this.Expansion.$el.detach();
    this.$el.after(this.Expansion.$el);
    this.Skeleton.$el.detach();
    this.$el.after(this.Skeleton.$el);

    this.setEditorPosition();
  },

  changePane: function( pane, sub ) {

    cs.tooltips.trigger( 'kill' );

    if (this.activePane != pane ) {
      cs.navigate.trigger('pane:switch');
      this.Pane.show( new this.panes[pane]() );
    }
    this.activePane = pane;

    this.Pane.currentView.triggerMethod( 'navigate' );

    cs.navigate.reply( 'active:pane', this.activePane );

    if ( sub ) {
    	this.Pane.currentView.triggerMethod( 'base:open:sub', sub );
    } else {
    	this.Pane.currentView.triggerMethod( 'base:close:sub' );
    }

    this.$('.cs-pane-content-inner').perfectScrollbar({
      suppressScrollX     : true,
      scrollYMarginOffset : 25
    });

  },

  sortableHover: function( e ) {
    Backbone.$(e.currentTarget).toggleClass( 'hover', (e.type === 'mouseenter') );
  },

  controlNotLive: function( control, message ) {

    if ( !_.contains( this.sentControlMessages, control ) ) {
      this.sentControlMessages.push(control)
      cs.message.trigger('notice', cs.l18n( message ) )
    }

  },

  growlNotice: function( message, title ) {
    this.growlMessage({
      title: cs.l18n('message-notice'),
      style: "notice",
    }, arguments );
  },

  growlSuccess: function() {
    this.growlMessage({
      title: cs.l18n('message-success'),
      style: "success",
    }, arguments );
  },

  growlError: function() {
    this.growlMessage({
      title: cs.l18n('message-error'),
      style: "error",
    }, arguments );
  },

  growlMessage: function( defaults, args ) {

    var opts = {
      message: '',
      duration: 10000,
    };

    if (args && args[0]) opts.message = args[0];
    if (args && args[1]) opts.duration = args[1];
    if (args && args[2]) opts.title = args[2];

    var settings = _.extend( defaults, opts );
    if (settings.duration < 5000 )
      settings.close = ''

    Backbone.$.growl( settings );

    var scrollbarWidth = cs.data.request('scrollbar:width');
    if ( scrollbarWidth > 0 ) {
      Backbone.$('#growls').css( {right: scrollbarWidth} );
    }

  },

  getScrollbarWidth: function() {
    var outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.width = '100px';
    outer.style.msOverflowStyle = 'scrollbar';
    document.body.appendChild(outer);
    var widthNoScroll = outer.offsetWidth;
    outer.style.overflow = 'scroll';
    var inner = document.createElement('div');
    inner.style.width = '100%';
    outer.appendChild(inner);
    var widthWithScroll = inner.offsetWidth;
    outer.parentNode.removeChild(outer);
    return widthNoScroll - widthWithScroll;
  },

  scrollbarUpdate: function() {
    this.$('.cs-pane-content-inner').perfectScrollbar('update');
  },

  setEditorPosition: function() {
  	Backbone.$('#cornerstone').toggleClass( 'cs-right', cs.options.request( 'editor:position' ) == 'right' );
  },

  keybindingToggleClasses: function() {
		this.listenTo( cs.events, 'delete:confirm:key', function( state){
			Backbone.$('body').toggleClass('cs-delete-confirm', state );
		});
  },

  togglePreviewPointerEvents: function( state ) {
  	Backbone.$('#preview').toggleClass( 'no-pointer' );
  },

  toggleDragging: function( state ) {
  	Backbone.$('body').toggleClass( 'cs-hide-cursor', state );
  }

});

},{"../extra/expansion":140,"../inspector/inspector":146,"../layout/layout":147,"../library/element-library":150,"../settings/settings":178,"./footer":155,"./header":156,"./skeleton":159}],155:[function(require,module,exports){
var ViewExpand  = require('../extra/expand')
  , ViewConfirm = require('../extra/confirm')
  , ViewHome    = require('../extra/home')
  , ViewRespond = require('../extra/respond')
  , ViewOptions = require('../extra/options')
  , ViewSaveComplete = require('../extra/save-complete');


// EditorFooter
module.exports = CS.Mn.ItemView.extend({

  template: 'main/footer',
  ui: {
    'home': 'button.home',
    'collapse': 'button.collapse',
    'helptext': 'button.help-text',
    'skeleton': 'button.skeleton-mode',
    'respond': 'button.respond',
    'save': 'button.save',
  },

  events: {
    'click @ui.home': 'toggleHome',
    'click @ui.collapse': 'toggleCollapse',
    'click @ui.helptext': 'toggleHelpText',
    'click @ui.skeleton': 'toggleSkeletonMode',
    'click @ui.respond': 'toggleRespond',
    'click @ui.save': 'save',
  },

  // colorTempTimer: 30 * 1000, // Check every 30 seconds (change 30 to 1 for testing)
  // colorTempThreshold: 30 * 60 * 1000, // Max out at 30 minutes. (change 60 to 1 for testing)
  // colorTempSteps: [ '#d0d0d0', '#ffd700', '#ffa500', '#ff4500', '#ff0000' ],

  initialize: function() {

    this.listenTo(cs.extra, 'flyout:updated', this.toggleMode );
    this.listenTo(cs.data, 'save:success', this.saveComplete );
    this.listenTo(cs.data, 'save:error', this.saveComplete );
    this.listenTo(cs.tooltips, 'kill', this.killTooltip )

    Backbone.$('#cornerstone').on('mouseenter mouseleave', '[data-tooltip-message]', _.bind( this.toggleTooltip, this ) );

    this.modules = {
      home: new ViewHome(),
      expand: new ViewExpand(),
      respond: new ViewRespond(),
      save: new ViewSaveComplete(),
      confirm: new ViewConfirm(),
    };

    this.panels = _.pick(this.modules, 'home', 'expand', 'respond', 'save', 'confirm' );

    cs.extra.on( 'collapse', function( state ) {
       cs.global.trigger( 'collapse', ( state == 'on' ) );
    });

    this.panelMode = 'none';
    this.listenTo( cs.extra, 'flyout', function( mode, stay ){
      var updated =  ( mode != this.panelMode );
      cs.extra.trigger( 'flyout:updated', mode, updated, this.panelMode );
      if ( mode != this.panelMode ) {
        cs.extra.trigger( 'flyout:' + mode );
      }
      this.panelMode = (updated) ? mode : 'none';

      var active = ( 'none' != this.panelMode );
      var mode = this.panelMode;
      if (active) {
        _.delay(function(){
          Backbone.$('.cs-editor').addClass( 'flyout' ).attr( 'data-flyout', mode );
          cs.navigate.trigger( 'scrollbar:update' );
        }, 650 );
      } else {
        Backbone.$('.cs-editor').removeClass( 'flyout' ).attr( 'data-flyout', 'none' );
        cs.navigate.trigger( 'scrollbar:update' );
      }
    });

    this.listenTo( cs.extra, 'toggle', function( item ) {

    	_.defer(_.bind(function(){
    		this.$( 'button[data-toggle="'+ item + '"]' ).toggleClass('active', cs.options.request( item ) );
    	}, this ) );

    });

    this.listenTo( cs.global, 'preview:failure', function( ) {
			this.$('button.skeleton-mode').prop('disabled',true);
		} );

    // Start save button color temp after rendering
    // this.once('render', function() {
    //   this.colorTempInterval = setInterval( _.bind( this.saveColorTemp, this ), this.colorTempTimer );
    // });
  },

  onRender: function() {
    var $extra = this.$('.cs-editor-extra');
    _.each(this.modules,function(item){
      $extra.append(item.render().$el);
    });

    this.$( 'button[data-toggle]' ).each(function(){
    	var $this = Backbone.$(this);
    	$this.toggleClass('active', cs.options.request( $this.attr('data-toggle') ) )
    });

  },

  toggleTooltip: function(e) {

    var message = Backbone.$(e.currentTarget).data('tooltip-message');

    var show = (e.type === 'mouseenter' && message && cs.options.request( 'help:text' ) );

    if ( show )
      this.$('.cs-tooltip-inner').text( message );

    if (this.tooltipTimer) {
      window.clearTimeout(this.tooltipTimer);
      this.tooltipTimer = undefined;
      return;
    }

    this.tooltipTimer = window.setTimeout( _.bind( function() {
      this.tooltipTimer = undefined;
      this.$('.cs-tooltip-outer').toggleClass('active', show );
    }, this ), (show) ? 333 : 250 );

  },

  killTooltip: function() {
    this.tooltipTimer = undefined;
    this.$('.cs-tooltip-outer').toggleClass('active', false );
  },

  toggleHome: function() {
    cs.extra.trigger( 'flyout', 'home' );
  },

  toggleCollapse: function() {
    cs.extra.trigger( 'flyout', 'collapse' );
  },

  toggleHelpText: function() {
    cs.extra.trigger( 'toggle', 'help:text' );
  },

  toggleSkeletonMode: function() {
    cs.extra.trigger( 'toggle', 'skeleton:mode' );
  },

  toggleRespond: function( e ) {
    cs.extra.trigger( 'flyout', 'respond' );
  },

  save: function() {
    this.$('button.save').prop( 'disabled', true );
    cs.events.trigger( 'action:save' );
  },

  saveComplete: function() {
    //this.saveColorTemp();
    this.$('button.save').removeProp( 'disabled' );
    this.panels.save.triggerMethod('save:complete');
  },

  saveError: function() {
    //this.saveColorTemp();
    this.$('button.save').removeProp( 'disabled' );
    cs.message.trigger( 'error', cs.l18n( 'save-error' ), 30000 );
  },

  // saveColorTemp: function() {
  //   var savedLast, diff, progress, colorIndex;

  //   savedLast = cs.data.request( 'saved:last' );

  //   diff = Date.now() - ( (savedLast) ? savedLast.toDate().getTime() : cs.bootTime );
  //   progress = diff / this.colorTempThreshold; // % of threshold
  //   colorIndex = Math.min( Math.floor( this.colorTempSteps.length * progress ), this.colorTempSteps.length - 1 );

  //   this.$('button.save').css('color', this.colorTempSteps[colorIndex] );

  // },

  toggleMode: function( mode, updated, prev ) {

    this.$('nav button.has-flyout').removeClass('active');
    _.each(this.panels,function(item){
      item.$el.removeClass('active');
    });

    if ( updated ) {
      this.$('button.has-flyout.' + mode ).addClass('active');
      this.$('.cs-editor-extra .cs-' + mode ).addClass('active');
    }

  },

});
},{"../extra/confirm":138,"../extra/expand":139,"../extra/home":141,"../extra/options":142,"../extra/respond":143,"../extra/save-complete":144}],156:[function(require,module,exports){
// EditorHeader
module.exports = CS.Mn.ItemView.extend({
  tagName: 'nav',
  template: 'main/header',

  events: {
    'click button.layout':    'layout',
    'click button.inspector': 'inspector',
    'click button.elements':  'elements',
    'click button.settings':  'settings'
  },

  initialize: function() {
    this.listenTo(cs.navigate, 'pane', this.changePane )
  },

  onRender: function() {
    this.changePane( cs.navigate.request( 'active:pane' ) );
  },

  layout: function () {
    cs.navigate.trigger('pane', 'layout' );
  },

  inspector: function () {
    cs.navigate.trigger('pane', 'inspector' );
  },

  elements: function () {
    cs.navigate.trigger('pane', 'elements' );
  },

  settings: function () {
    cs.navigate.trigger('pane', 'settings' );
  },

  changePane: function( pane ) {
    this.$( '.' + pane ).addClass('active').siblings().removeClass('active');
  }
});
},{}],157:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
	template: 'observer',
	className: 'cs-observer',
	initialize:function(){

		this.lazyRender = _.debounce( this.renderNow, 100 );
		this.throttleTimer = 50;

		this.observing = null;
		this.tooltipText = 'Element';
		this.canPreview = true;
		this.dimensionTarget = null;

		this.listenTo( cs.observer, 'start', this.observeStart );
		this.listenTo( cs.observer, 'end', this.observeEnd );
		this.listenTo( cs.global,   'kill:observer', this.kill );
		this.listenTo( cs.observer, 'kill', this.kill );

		this.$wrapper = Backbone.$('#cs-content');

	},

	setObserver: function( view, immediate ) {

		clearInterval( this.renderInterval );

		this.coordinates = { top: 0, left: 0, height: 0, width: 0 };
		this.observing = view;


		if ( this.observing ) {
			var type = view.model.get('_type');
			var definition = cs.elementLibrary.lookup( type );
			var flags = definition.get( 'flags' );
			this.canPreview = flags.can_preview || true;
			this.dimensionTarget = flags.dimension_target || null;
			this.setTooltip( view.model, type );
		}

		(immediate) ? this.renderNow() : this.lazyRender();
	},

	setTooltip: function( model, type ) {

		var text;

		switch ( type ) {
      case 'section':
        text = cs.l18n('section-format').replace('%s', model.get('title') );
        break;
      case 'row':
        text = cs.l18n('row-numeric').replace('%s', model.getIndex() + 1 );
        break;
      case 'column':
        text = cs.l18n('column-format').replace('%s', model.get('size') );
        break;
      default:
      	text = model.definition.get('ui').title;
      	break;
    }

    if ( !this.canPreview ) {
    	text = cs.l18n('no-preview').replace('%s', text );
    }

    this.tooltipText = text;

	},

	observeStart: function( view, immediate ) {
  	if ( !_.isNull( this.observing ) && this.observing.cid == view.cid) return;
  	this.setObserver( view, immediate || false );
  },

  observeEnd: function( view, immediate ) {
  	if ( _.isNull( this.observing ) || this.observing.cid != view.cid ) return;
		this.setObserver( null, immediate || false );
	},

	kill: function() {
		this.setObserver( null, true );
	},

	renderNow: function() {

		clearInterval( this.renderInterval );

		if ( _.isNull( this.observing ) || cs.observer.request( 'get:collapse' ) ) {
			this.$el.hide();
			return;
		}

		this.renderLoop();
		this.renderInterval = setInterval( _.bind( this.renderLoop, this ), this.throttleTimer );

	},

	renderLoop: function() {

		var $el = ( this.dimensionTarget ) ? this.observing.$el.find( this.dimensionTarget ) : this.observing.$el;

		if ( $el.length < 1 )
			$el = this.observing.$el;

		var offset = $el.offset();
		var newHeight = $el.outerHeight();
		var newWidth = $el.outerWidth();

		if ( this.coordinates.width == newWidth && this.coordinates.height == newHeight  )
			return;

		if ( ! offset )
			return;

		this.coordinates = {
			top: offset.top - this.$wrapper.offset().top,
			left: offset.left - this.$wrapper.offset().left,
			width: newWidth,
			height: newHeight
		};

		this.render();
		this.$el.css( this.coordinates );

		if ( this.coordinates.top < 25 ) {
			this.$('.cs-observer-tooltip').removeClass('top');
		}

	},


	onRender: function() {

		if ( _.isNull( this.observing ) || cs.observer.request( 'get:collapse' ) ) {
			this.$el.hide();
			return;
		}

		this.$el.toggleClass('cs-observer-no-preview', !this.canPreview );
		this.$el.show();

	},

	serializeData: function() {
		return _.extend( CS.Mn.ItemView.prototype.serializeData.apply( this, arguments ), {
			tooltip: this.tooltipText
		} );
	},

});
},{}],158:[function(require,module,exports){
module.exports = Cornerstone.Mn.CompositeView.extend({

	template: false,

	emptyView: CS.Mn.ItemView.extend({
		className: 'cs-empty-rows',
		template: 'empty-rows',
	}),

	getChildView: function( item ) {
		return cs.component('view-loader').elementLookup( item.get('_type') );
	},

	initialize:function(){

		this.collection = this.model.elements;

		this.listenToOnce( cs.global, 'preview:primed', function() {
			this.$el.removeClass('cs-preview-loading');
		} );

		this.listenTo( this.collection, 'sort', this.render );

    this.observerView = new (require('./observer.js'))();

		this.listenTo(  cs.global, 'set:collapse', this.toggleCollapse );
		this.listenTo(  cs.global, 'dragging', this.toggleDragging );
		this.listenTo(  cs.preview, 'dragging', this.toggleDragging );

		this.listenTo( cs.preview, 'autoscroll', _.debounce( _.bind( this.autoScroll, this ), 250, true ) );

		this.drake = Cornerstone.Vendor.dragula({
		  isContainer: function (el) {
		    return !!el.classList && ( el.classList.contains('x-column') || el.classList.contains('cs-indicator-container') );
		  },
		  moves: function( el, source, handle, sibling ) {
		  	return !!el.classList && ( el.classList.contains('cs-preview-element-wrapper') || el.classList.contains('cs-indicator') );
		  },
			accepts: function( el, target, source, sibling ) {
				return !!el.classList && ( el.classList.contains('cs-preview-element-wrapper') || el.classList.contains('cs-indicator') );
			},
			offset: function( offset, e, el ) {

				//if (!!el.classList && el.classList.contains('cs-indicator')) {
					offset.x = 0;
					offset.y = 0;
				//}

				return offset;
			},
		  copy: false,
		  revertOnSpill: true,
		  mirrorContainer: this.el
		});

		this.drake.on('drag', function( el, source ) {

			if ( !!el.classList && el.classList.contains('cs-indicator')  ) {

				if ( this.incoming ) {
					var event = new MouseEvent( 'mousedown', {
						'buttons': 1,
						'which': 1,
						'view': window,
						'bubbles': true,
						'cancelable': true,
						'synthetic': true,
					} );

	    		el.dispatchEvent(event, true);
				}

				this.incoming = false;

			} else {
				Backbone.$(el).trigger( 'dragula:lift' );
				Backbone.$(source).trigger( 'dragula:lift' );
				cs.global.trigger( 'drag:existing:start' );
			}
			cs.preview.trigger( 'dragging', true );
		});

		// allow mouseup in the editor to cancel our drag interaction
		this.listenTo( cs.global, 'drag:existing:end', function() {
			cs.preview.trigger( 'dragging', false );
		});

		this.drake.on('dragend', function( el ) {
			cs.preview.trigger( 'dragging', false );
			Backbone.$(el).trigger( 'dragula:dragend' );
		});

		this.drake.on('cancel', function( el, container, source ) {
			Backbone.$(source).trigger( 'dragula:cancel' );
			Backbone.$(container).trigger( 'dragula:cancel' );
		});

		this.drake.on('drop', function( el, target, source, sibling ) {
			Backbone.$(target).trigger( 'dragula:receive', [ el, source, sibling ] );
		});

		this.drake.on( 'over', function( el, container, source ) {
			Backbone.$(container).trigger( 'dragula:over' );
			Backbone.$(source).trigger( 'dragula:source:over' );
		});

		this.drake.on( 'shadow', function( el, container, source ) {
			Backbone.$(source).trigger( 'dragula:shadow' );
		});

		this.drake.on( 'out', function( el, container, source ) {
			Backbone.$(container).trigger( 'dragula:out' );
		});

		this.drake.on( 'cloned', _.bind( function( clone, original, type ) {
			if (type != 'mirror') return;
			this.$ghost = Backbone.$( clone );
			this.$ghost.addClass('cs-indicator');
			Backbone.$(original).trigger( 'dragula:mirror', this.$ghost );
		}, this ) );

		cs.$indicator = Backbone.$('<div class="cs-indicator"></div>');

		document.addEventListener("dragleave", function( e ) {
			var $el;

			if ( !!e.target.classList && e.target.classList.contains('x-column') ) {
				$el = Backbone.$(e.target);
			} else {
				$el = Backbone.$(e.target).parent('.x-column');
			}

      if ( $el.length > 0 && !$el.csPointInsideElement( e.clientX, e.clientY ) ) {
        cs.$indicator.detach();
      }

		}, false);

		var stopDragging = _.bind(function() {
			cs.global.trigger('stop:dragging')
			cs.preview.trigger( 'dragging', false );

			cs.$ic[0].left = 0;
		  cs.$ic[0].top = 0;

			document.removeEventListener('mouseup', stopDragging, false);
		},this)




		this.listenTo( cs.global, 'drag:resume', function( type ) {

			if ( this.$ghost )
				this.$ghost.hide();

			document.addEventListener('mousemove', eventualResume, false);

		} );

		var eventualResume = _.bind( function( e ) {

			if ( this.$ghost )
				this.$ghost.show();

    	cs.global.trigger( 'drag:exit' );
      document.removeEventListener('mousemove', eventualResume, false);

    }, this );


		this.listenTo( cs.global, 'incoming:element', function( type, legacy ) {

			cs.incoming = {
				data: { _type: type },
				options: {},
				cid: 'new:' + type
			};

      cs.render.preRender( type );

      cs.$indicator.empty().detach();
      cs.$indicator.removeAttr('style');
      cs.$indicator.append( Backbone.$( cs.elementIcon(type) ) );

      if ( ! legacy ) document.addEventListener('mousemove', eventualDragStart, false);

    });

    var eventualDragStart = _.bind( function( e ) {

    	cs.global.trigger( 'drag:exit' );
    	cs.$indicator.detach();
    	cs.$ic.append( cs.$indicator );
    	cs.preview.trigger( 'dragging', true );

      this.drake.incoming = true;
      this.drake.start(cs.$indicator[0]);
      document.removeEventListener('mousemove', eventualDragStart, false);
      document.addEventListener('mouseup', stopDragging, false);

    }, this );

    this.on( 'drag:cancel', function() {
    	document.removeEventListener('mousemove', eventualDragStart, false );
    	this.drake.cancel();
    })

    cs.$ic = Backbone.$('<div class="cs-indicator-container"></div>');

	},

	onBeforeRender: function() {

		this.scrollTopCache = Backbone.$('body').scrollTop();

		Backbone.$('a').click( function( e ) {
			cs.preview.trigger('click:theme:a', e );
		}).attr('target','_blank');

	},

	onRender: function() {

		this.$el.append(cs.$ic);

		Backbone.$('html,body').scrollTop( this.scrollTopCache );

		this.$el.toggleClass('cs-editor-active', true );
		this.$el.toggleClass('cs-editor-inactive', false );

		this.$el.append( this.observerView.render().$el );

		_.defer( function(){
			cs.preview.trigger( 'responsive:text' );
		} );
	},

	toggleCollapse: function( state ) {
		this.$el.toggleClass('cs-editor-active', !state )
		this.$el.toggleClass('cs-editor-inactive', state )
	},

	toggleDragging: function( state, legacy ) {

		if (!state) {
			cs.$indicator.detach();
			if ( ! legacy ) {
				this.trigger( 'drag:cancel' );
			}
		}

		this.$el.toggleClass('gu-unselectable', state );

		Backbone.$('body')
			.toggleClass( 'cs-hide-cursor', state )
			.toggleClass( 'cs-indicate-invisible', state );

	},

	autoScroll: function( view ) {

		if ( view.$el.visible( true ) )
			return;

		var $from = Backbone.$( cs.config('scrollTopSelector') );
		var top = ($from.length > 0 ) ? $from.outerHeight() : 0;
		var offset = view.$el.offset().top - top - 27; // Magic number for observer height
		Backbone.$('html,body').animate( { scrollTop: offset }, 700, 'swing' );

	},

	onDestroy: function() {
		this.drake.destroy();
	}

});
},{"./observer.js":157}],159:[function(require,module,exports){
module.exports = CS.Mn.CompositeView.extend({

	className: 'cs-skeleton-content-outer',
  template: 'main/skeleton',
  childViewContainer: '.cs-skeleton-items',

  getChildView: function( item ) {
		return cs.component('view-loader').skeletonLookup( item.get('_type') );
	},

	events: {
		'dragula:receive': '_receiveElement'
	},



	initialize: function() {

		this.collection = this.model.elements;
		this.hoverRender = _.debounce( this._hoverRender, 45 );
		this.hoverTarget = null;

		this.listenTo( this.collection, 'sort', this.render );
		//this.listenTo( cs.events, 'resize:skeleton', this.windowResize );

		var handlers = require('../../utility/dragula-handlers');
		this.setupElementDragging( handlers );
		this.setupRowDragging( handlers );
		this.setupSectionDragging( handlers );

		this.listenTo(  cs.events, 'skeleton:dragging', this.toggleDragging );

    this.listenTo( cs.global, 'stop:dragging', function() {
			this.elementDrake.cancel();
			cs.global.trigger( 'dragging', false );
		});

    this.listenTo(  cs.events, 'skeleton:hover', this.hover );

    // Send a complimentary cancelation to the preview window
    // in case the mouse was outside the iframe
    this.listenTo( cs.global, 'drag:existing:start', function() {

    	var $preview = Backbone.$('#preview');
    	document.addEventListener( 'mouseup', mouseUp );
    	function mouseUp() {
    		document.removeEventListener( 'mouseup', mouseUp );
    		_.defer( function() {
    			cs.global.trigger( 'drag:existing:end');
    		});
    	}
		});

	},

	setupElementDragging: function( handlers ) {

		this.elementDrake = Cornerstone.Vendor.dragula({
		  isContainer: function (el) {
		    return !!el.classList && ( el.classList.contains('cs-skeleton-container-column') || el.classList.contains('cs-elements') );
		  },
		  moves: function( el, source, handle, sibling ) {
		  	return !!el.classList && ( el.classList.contains('cs-element-stub') && !el.attributes.draggable || ( el.classList.contains('cs-skeleton-item') && !el.classList.contains('is-child') && !el.classList.contains('layout') ) );
		  },
			accepts: function( el, target, source, sibling ) {
				if ( !!target.classList && target.classList.contains('cs-elements') )
					return false;
				return !!el.classList && ( el.classList.contains('cs-element-stub') || ( el.classList.contains('cs-skeleton-item') && !el.classList.contains('is-child') && !el.classList.contains('layout') ) );
			},
		  copy: function (el, source) {
			  return !!el.classList && el.classList.contains('cs-element-stub');
			},
			offset: function( offset, e, el ) {

				if ( !!el.classList ) {
					if ( el.classList.contains('cs-element-stub') ) {
						offset.x = 0;
						offset.y = 0;
					}
					if ( el.classList.contains('cs-skeleton-item') ) {
						offset.x = 0;
						offset.y = Backbone.$(el).height() / 2; // snap to vertical center
					}
				}

				return offset;
			},
			mirrorContainer: Backbone.$('#before')[0],
		  revertOnSpill: true,
		});

		var skeleton = this;

		this.elementDrake.on( 'cloned', handlers.editorCloned );
		this.elementDrake.on( 'dragend', handlers.skeletonEnd );
		this.elementDrake.on( 'drag', handlers.skeletonStart );
		this.elementDrake.on( 'cancel', handlers.cancel );
		this.elementDrake.on( 'remove', handlers.cancel );
		this.elementDrake.on( 'drop', handlers.drop );
		this.elementDrake.on( 'over', handlers.over );
		this.elementDrake.on( 'shadow', handlers.shadow );
		this.elementDrake.on( 'out', handlers.out );

		this.listenTo( cs.global, 'incoming:element', function( type ) {

			cs.incoming = {
				data: { _type: type },
				options: {},
				cid: 'new:' + type
			};

    });

	},

	setupSectionDragging: function( handlers ) {

		this.sectionDrake = Cornerstone.Vendor.dragula({
			isContainer: function (el) {
				return !!el.classList && ( el.classList.contains('cs-skeleton-items') );
			},
			moves: function( el, source, handle, sibling ) {
				return Backbone.$(handle).is('.cs-skeleton-item.section > .cs-skeleton-handle > .cs-skeleton-title');
			},
			accepts: function( el, target, source, sibling ) {
				return Backbone.$(el).is('.cs-skeleton-item.section');
			},
			offset: function( offset, e, el ) {

				if ( !!el.classList && el.classList.contains('cs-skeleton-item')) {
					offset.y = Backbone.$(el).height() / 2; // snap to vertical center
				}

				return offset;
			},
			mirrorContainer: Backbone.$('#before')[0],
		});

		this.sectionDrake.on( 'dragend', handlers.skeletonEnd );
		this.sectionDrake.on( 'drag', handlers.skeletonStart );
		this.sectionDrake.on( 'cancel', handlers.cancel );
		this.sectionDrake.on( 'remove', handlers.cancel );
		this.sectionDrake.on( 'drop', handlers.drop );
		this.sectionDrake.on( 'over', handlers.over );
		this.sectionDrake.on( 'out', handlers.out );

	},

	setupRowDragging: function( handlers ) {

		this.rowDrake = Cornerstone.Vendor.dragula({
			isContainer: function (el) {
				return !!el.classList && ( el.classList.contains('cs-skeleton-container-section') );
			},
			moves: function( el, source, handle, sibling ) {
				// Ensure a single remaining row won't budge
				return ( Backbone.$(handle).is('.cs-skeleton-item.row > .cs-skeleton-handle > .cs-skeleton-title') && Backbone.$(el).siblings().length > 0 );
			},
			accepts: function( el, target, source, sibling ) {
				return Backbone.$(el).is('.cs-skeleton-item.row');
			},
			offset: function( offset, e, el ) {

				if ( !!el.classList && el.classList.contains('cs-skeleton-item')) {
					offset.y = Backbone.$(el).height() / 2; // snap to vertical center
				}

				return offset;
			},
			mirrorContainer: Backbone.$('#before')[0],
		  revertOnSpill: true,
		});

		this.rowDrake.on( 'dragend', handlers.skeletonEnd );
		this.rowDrake.on( 'drag', handlers.skeletonStart );
		this.rowDrake.on( 'cancel', handlers.cancel );
		this.rowDrake.on( 'remove', handlers.cancel );
		this.rowDrake.on( 'drop', handlers.drop );
		this.rowDrake.on( 'over', handlers.over );
		this.rowDrake.on( 'out', handlers.out );

	},

	onRender: function() {
		this.$('.cs-skeleton-content-inner').perfectScrollbar({
      scrollYMarginOffset: 10,
      wheelPropagation: true
    });
    _.defer( _.bind( this.deferRender, this ) );
	},

	deferRender: function() {
		this.$('.cs-skeleton-content-inner').perfectScrollbar('update');
	},

	windowResize: function() {
		// var height = cs.data.request( 'skeleton:preview:height' ) ;
		// var windowHeight = Backbone.$(window).height();
		// console.log( 'previewHeight', height );
		// var ratio = 2;
		// var offset = (windowHeight - height) * ratio;
		// Backbone.$('.cs-preview').css({ 'bottom' : -offset + 'px', 'height': 'auto' });
	},

	toggleDragging: function( state, type ) {

		this.isDragging = state;
		this.$el.toggleClass('gu-unselectable', state );

		var type = type || '';

		this.$el.removeClass('dragging-section dragging-row dragging-column dragging-element');
		this.$el.attr('data-element-type', type );

		if ( state ) {
			var classType = ( _.contains( ['section', 'row', 'column' ], type ) ) ? type : 'element';
			this.$el.addClass( 'dragging-' + classType );
			this.setHover(null);
		} else {
			this.elementDrake.cancel();
			this.rowDrake.cancel();
			this.sectionDrake.cancel();
		}

	},

	hover: function( state, view ) {

		if (this.isDragging )
			return this.setHover( null );

		if ( state ) {
			if ( !_.isNull( this.hoverTarget ) && this.hoverTarget.cid == view.cid) return;
	  	this.setHover( view );
		} else {
			if ( _.isNull( this.hoverTarget ) || this.hoverTarget.cid != view.cid ) return;
			this.setHover( null );
		}

	},

	setHover: function( view ) {
		this.hoverTarget = view;
		this.hoverRender();
	},

	_hoverRender: function( state ) {
		cs.events.trigger('skeleton:hover:toggle', ( this.hoverTarget && this.hoverTarget.cid ) ? this.hoverTarget.cid : null )
	},

	_receiveElement: function( e, el, source, sibling ) {
		e.stopPropagation();
		_.defer( _.bind( this._placeElement, this ), ( sibling ) ? Backbone.$(sibling).index() - 1 : this.collection.length );
	},

	_placeElement: function( position ) {

		if ( !cs.incoming ) return;

		var data = ( !_.isFunction( cs.incoming.toJSON ) ) ? cs.incoming.data : cs.incoming.toJSON();
		var newModel = this.model.elements.create( data, _.clone( cs.incoming.options ), {
			position: position,
			replace: cs.incoming,
			after: function( model ) {
				cs.incoming = false;
			}
		} );

	}

});
},{"../../utility/dragula-handlers":93}],160:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	toggle: function( e ) {

		var href
		var $this   = this.$(e.target)
		var $target = this.$( $this.attr('data-target') || e.preventDefault() || $this.attr('href') )
		var data    = $target.data('bs.collapse')
		var option  = data ? 'toggle' : $this.data()
		var parent  = $this.attr('data-parent')
		var $parent = parent && this.$(parent)

		if (!data || !data.transitioning) {
		if ($parent) $parent.find('[data-toggle="collapse"][data-parent="' + parent + '"]').not($this).addClass('collapsed')
			$this[$target.hasClass('in') ? 'addClass' : 'removeClass']('collapsed')
		}

		jQuery.fn.collapse.call($target, option)

	},

	onClickBeforeInspect: function( e ) {
		if ( e.target.className.indexOf('x-accordion-toggle') >= 0 ) {
			this.toggle(e);
		}
	},

});
},{}],161:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	elementEvents: {
		'click button.close' : 'closeButton'
	},

	closeButton: function( e ) {
		e.preventDefault();
	}
});
},{}],162:[function(require,module,exports){
module.exports = CS.Mn.CompositeView.extend( {

	remoteRender: true,
	template: false, // <div class="cs-inception"></div>
	childViewContainer: '@ui.root',
	className: 'cs-preview-element-wrapper',
	attributes: function() {
		return {
			'data-element': this.model.get( '_type' )
		};
	},
	draggable: true,
	htmlhint: { 'tagname-lowercase': false, 'attr-lowercase': false, 'attr-value-double-quotes': false, 'doctype-first': false, 'tag-pair': true, 'spec-char-escape': false, 'id-unique': false, 'src-not-empty': false, 'attr-no-duplication': false, 'title-require': false },

	events: function() {

		var events = {
			'click a': '_preventLinkout'
		};

		var inspectable = _.result( this, 'inspectable' );
		if ( inspectable || _.isUndefined( inspectable ) ) {

			events = _.extend( events, {
				'click': '_click',
				'mouseover': '_observeStart',
				'mouseout': '_observeEnd'
			}, this._setupAutoFocus() );

		}

		var draggable = _.result( this, 'draggable' );
		if ( draggable || _.isUndefined( draggable ) ) {

			events = _.extend( events, {
				'dragula:lift': '_dragLift',
				'dragula:dragend': '_dragEnd',
				'dragula:mirror': '_dragMirror'
			} );

		}

		return _.extend( events, _.result( this, 'elementEvents' ) || {} );
	},

	getChildView: function( item ) {
		return cs.component( 'view-loader' ).elementLookup( item.get( '_type' ) );
	},

	constructor: function( options ) {

		CS.Mn.CompositeView.apply( this, arguments );

		this.ignoredAttributes = [ 'rank' ];
		this._setupChildren();

		this._repaint = _.debounce( _.bind( function() {
			try {
				this.render();
			} catch ( e ) {
				if ( 'ViewDestroyedError' == e.name ) return;
				console.log( 'Cornerstone Render Exception', e );
			}
		}, this ), 2 );

		this._updateMarkup = _.debounce( _.bind( this.__updateMarkup, this ), 2 );

		if ( this.model.collection )
			this.modelIndex = this.model.collection.indexOf( this.model );

		this.listenTo( this.model, 'change', this._updateMarkupOnChange );
		this.listenTo( this.model, 'remote:render', this._repaint );
		this.on( 'render', this._baseRender );

		this.listenTo( this.model, 'observe:start', function() {
			cs.observer.trigger( 'start', this );
		} );

    this.listenTo( this.model, 'observe:end', function() {
			cs.observer.trigger( 'end', this );
    } );

    // Privately attach event handlers so we can use the triggerMethod hooks in inheriting views
    this.on( 'render:template', this._onRenderTemplate );
    this.on( 'template:data:ready', function( data ) {
			this.once( 'render', function() {
				this.triggerMethod( 'construct:element', data );
			});
    } );

		this.triggerMethod( 'element:init' );

		this.listenTo( cs.global, 'autoscroll', function( cid ) {
			if ( this.model && this.model.cid == cid )
				cs.preview.trigger( 'autoscroll', this );
		} );

		this.listenTo( cs.events, 'preview:resize', this.exposeVisibility );

	},

	_setupChildren: function() {

		if ( this.model.definition.get( 'flags' ).dynamic_child ) {
			this.collection = this.model.elements;
			this.ignoredAttributes.push( 'elements' );
		}

		if ( this.model.definition.get( 'flags' ).child ) {
			this.listenTo( this.model, 'parent:change', function() {
				this._updateMarkup();
			} );
		}

	},

	resortView: function() {
		this._updateMarkup(); // Override instead of using default render call
	},

	_updateMarkupOnChange: function( model ) {
		if ( _.isEmpty( _.omit( model.changed, this.ignoredAttributes ) ) )
			return;
		this._updateMarkup();
	},

	__updateMarkup: function( now ) {
		if ( this.remoteRender ) {
			cs.render.triggerMethod( 'queue', this.model );
		} else {
			return ( now ) ? this.render() : this._repaint();
		}
	},

	serializeData: function() {
		var base = CS.Mn.CompositeView.prototype.serializeData.apply( this, arguments );
		var elementData = _.result( this, 'elementData' ) || {};
		return _.defaults( _.extend( base, elementData ), this.model.getDefaults() );
	},

	attachElContent: function( html ) {

    if ( this.remoteRender ) {

			html = cs.render.getCache( this.model );

			if ( _.isFunction( html ) )
				html = CS.Mn.Renderer.render( html, this.serializeData() );

    }

    if ( html ) {

			if ( this.model.definition.get( 'flags' ).htmlhint ) {
				if ( ! _.isEmpty( Cornerstone.Vendor.HTMLHint.verify( html, this.htmlhint ) ) )
					html = Backbone.$( '<div>' ).text( html ).html(); // Encode html with unclosed tags.
			}

			// errors = _.filter( errors, function( error ) {
			// 	if ( error.message.indexOf('[ <p> ]') !== -1 ) return false;
			// 	if ( error.message.indexOf('[ </p> ]') !== -1 ) return false;
			// 	if ( error.raw =="</p>" ) return false;
			// 		return true;
			// });

			this.$el.html( html );

    }

    return this;
  },

  exposeVisibility: function() {
		var invisible = this.$el.outerHeight() <= 0;
		this.$el.toggleClass( 'cs-invisible', invisible );
		this.triggerMethod( 'visibility:exposed', invisible );
  },

  onChildviewVisibilityExposed: function( item ) {
		this.exposeVisibility(); // Repropogate visibility changes
  },

	// Bind the @root ui dynamically. Use inception parent if it exists, otherwise this.$el
	// This is used for our composite view's childViewContainer
	bindUIElements: function() {
		if ( ! this.ui ) this.ui = {};
		CS.Mn.CompositeView.prototype.bindUIElements.apply( this, arguments );
		var $inception = this.$( '.cs-inception' );
		this.ui.root = ( $inception.length ) ? $inception.parent() : this.$el;
	},

  // After UI is bound, remove inception placeholder if it exists
	_onRenderTemplate: function() {
		var $inception = this.$( '.cs-inception' );
		if ( $inception.length ) $inception.detach();
	},

	_baseRender: function() {

		this.triggerMethod( 'before:shortcode:init' );

		window.xData.base.processElements( null, this.$el );

		cs.preview.trigger( 'responsive:text', this );

		this.triggerMethod( 'after:element:render' );

		if ( this.remoteRender ) {
			_.defer( _.bind( function() {
				if ( this._emptyDetection() ) {
					this.triggerMethod( 'render:empty' );
				}
			}, this ), 0 );
		}

		_.defer( _.bind( this.exposeVisibility, this ) );

	},

	onRenderEmpty: function() {
		this._renderEmpty();
	},

	_renderEmpty: function() {
		var $html = Backbone.$( CS.Mn.Renderer.render( 'empty-element', this.serializeData() ) );
		this.$el.append( $html );
	},

  _emptyDetection: function() {

		if ( _.isFunction( this.emptyDetection ) )
			return this.emptyDetection();

		if ( this.model.definition.get( 'flags' ).empty || this.model.definition.get( 'flags' ).no_height_check ) {
			return false; // Detection handled server side, but aborted here.
		}

    return ( this.$el.shadowHeight() < 1 );

  },

	_setupAutoFocus: function() {

		var events = {}, ui = this.model.definition.get( 'ui' );

		if ( ! ui.autofocus ) return events;

		_.each( ui.autofocus, function( value, key ) {
		var selector = 'click ' + value;
			events[selector] = function() {
				 cs.global.trigger( 'auto:focus', key );
			};
		});

		return events;

	},

  _preventLinkout: function( e ) {
		e.preventDefault();
  },

	_click: function( e ) {
		this.triggerMethod( 'click:before:inspect', e );
		e.stopPropagation();
		cs.global.trigger( 'inspect', this.model );
	},

	_dragLift: function( e ) {
		this.$el.addClass( 'cs-dragging' );
		cs.incoming = this.model;
	},

	_dragEnd: function( e ) {
		this.$el.removeClass( 'cs-dragging' );
	},

	_dragMirror: function( e, clone ) {
		Backbone.$( clone ).empty().append( Backbone.$( cs.elementIcon( this.model.get( '_type' ) ) ) );
	},

	_observeStart: function( e ) {
		e.stopPropagation();
		this.model.trigger( 'observe:start' );
	},

	_observeEnd: function( e ) {
		this.model.trigger( 'observe:end' );
	}

} );

},{}],163:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	onAfterElementRender: function() {

    _.defer( _.bind( function(){
    	this.$('.x-card-outer').trigger('cs:setcardheight')
    }, this ) );

    this.$('a').click( function(e) {
  		e.preventDefault();
  	});

	}

});
},{}],164:[function(require,module,exports){
module.exports = Cornerstone.ElementViews.Base.extend({

	remoteRender: false,
	draggable: false,

	elementEvents: {
		'click svg.cs-custom-icon': 'clickIcon',
		'dragula:send': 'sendElement',
		'dragula:receive': 'receiveElement',
		'dragula:cancel': 'dragCancel',
		'dragula:over': 'dragOver',
		'dragula:out': 'dragOut',
		'dragula:shadow': 'dragShadow',
		'dragula:lift': 'dragLift',
		'dragula:source:over': 'dragSourceOver',
		'drop.h5s': 'receiveNew',
		'dragenter.h5s': 'dragEnterH',
		'dragover.h5s': 'dragOverH'
	},

	initialize: function() {

		this.checkDragOverStart = _.once( this.dragOverStart );

		this.checkDragLeave = _.debounce( this.dragLeave, 50 );
		this.detachIndicator = _.debounce( this._detachIndicator, 50 );
		this.throttleSetDragIndicator = _.throttle( _.bind( this.setDragIndicator, this ), 125, { leading: false, trailing: false } );
		this.legacyBefore = null;
		this.$empty = Backbone.$( cs.template( 'empty-column' )() );

	},

	receiveElement: function( e, el, source, sibling ) {

		cs.observer.trigger( 'kill' );

		var isNew = ! _.isFunction( cs.incoming.toJSON );
		var data = ( isNew ) ? cs.incoming.data : cs.incoming.toJSON();

		var shadow = cs.incoming.cid;
		var newModel = this.model.elements.create( data, _.clone( cs.incoming.options ), {
			position: ( sibling ) ? Backbone.$( sibling ).index() - 1 : this.collection.length,
			replace: cs.incoming,
			after: function( model ) {
				cs.render.shadow( model, shadow );
			}
		} );

		if ( isNew )
			cs.global.trigger( 'inspect', newModel );

		this.emptyClassCheck();

	},

	receiveNew: function( e ) {

		this.receiveElement( null, null, null, this.legacyBefore );
		cs.preview.trigger( 'dragging', false );

	},

	dragOver: function() {
		this.$el.toggleClass( 'cs-receiving', true );
	},

	dragOut: function() {
		this.$el.toggleClass( 'cs-receiving', false );
	},

	dragCancel: function() {
		this.$empty.detach();
		this.$el.removeClass( 'cs-empty' );
	},

	dragShadow: function() {
		if ( 1 === this.collection.length && this.$el.children().length <= 1 )
			this.emptyClassCheck( true );
	},

	clickIcon: function( e ) {
		e.stopPropagation();
		cs.global.trigger( 'nav:kylelements' );
	},

	onRemoveChild: function() {
		this.emptyClassCheck();
	},

	onAddChild: function( child ) {
		this.emptyClassCheck();
		child.triggerMethod( 'added:to:column' );
	},

	emptyClassCheck: function( force ) {

		this.$empty.detach();
		this.$el.removeClass( 'cs-empty' );

		if ( this.collection.isEmpty() || force ) {
			this.$el.append( this.$empty );
			this.$el.addClass( 'cs-empty' );
			this.$( '.cs-empty-column' ).removeAttr( 'style' );
		}

	},

	dragOverStart: function( ) {
		this.$el.toggleClass( 'cs-receiving', true );
		cs.observer.trigger( 'in', this, true );
	},

	dragEnterH: function() {
		this.checkDragOverStart();
		this.checkDragLeave();
	},

	dragOverH: function( e ) {

		this.checkDragOverStart();
		this.checkDragLeave();

			if ( e.originalEvent.preventDefault )
			e.originalEvent.preventDefault();

		this.throttleSetDragIndicator( e.originalEvent.pageY );

	},

	dragLeave: function() {
		this.$el.toggleClass( 'cs-receiving', false );
		this.checkDragOverStart = _.once( this.dragOverStart );
	},

	setDragIndicator: function( y ) {

		cs.$indicator.detach();
		if ( this.collection.length <= 0 ) return;

		var index = 0;
		var model = this.collection.find( _.bind( function( model ) {

			var view = this.children.findByModel( model );

			if ( view.$el.shadowHeight < 1 ) return false;

			if ( y > view.$el.offset().top + ( view.$el.outerHeight() / 2 ) ) {
				++index;
			} else {
				return true;
			}

			return false;

		}, this ) );

		this.legacyBefore = null;

		if ( this.collection.length == index ) {
			this.$el.append( cs.$indicator );
		} else {
			var before = this.children.findByModel( model );
			this.legacyBefore = before.el;
			before.$el.before( cs.$indicator );
		}

	},

	onElementInit: function() {

	},

	onConstructElement: function( data ) {

		classes = [ 'x-column', 'x-sm' ];
		styles = {};

		classes.push( 'x-' + data.size.replace('/','-') );

		if ( 'none' != data.text_align ) {
			classes.push( cs.classMap( 'text_align', data.text_align ) );
		}

		if ( _.isArray( padding = _.clone( data.padding ) ) ) {
			padding.pop();
			styles['padding'] = padding.join(' ');
		}

		if ( _.isArray( borderWidth = _.clone( data.border_width ) ) ) {
			borderWidth.pop();
			if ( _.unique(borderWidth) != '0px' ) {
				styles['border-width'] = borderWidth.join(' ');
				styles['border-color'] = data.border_color
				styles['border-style'] = data.border_style
			}
		}


		styles['background-color'] = ( '' != data.bg_color ) ? data.bg_color : 'transparent';

		if ( '' != data.id ) {
			this.$el.attr( 'id', data.id );
		}

		classes.push( data['class'] );
		this.$el.attr('class', classes.join(' '));
		delete classes;

		this.$el.removeAttr('style');
		this.$el.css(styles);

		if ( '' != data.style ) {
			this.$el.attr('style', this.$el.attr('style') + data.style );
		}

		this.emptyClassCheck();

	},

	onRenderEmpty: function() {
		return false;
	}

})
},{}],165:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	template: _.template( '<div></div>' ),
	remoteRender: false,
	minHeight: 10,

	onConstructElement: function( data ) {

		var classes, styles, visibility, customID, customStyle, size;

		var $gap = this.$( 'div' );

		classes = [ 'cs-empty-element', 'cs-gap' ];
		styles = {};

		classes = _.union( classes, cs.classMap( 'visibility', data.visibility ) );

		if ( '' != data.id ) $gap.attr( 'id', data.id );

		classes.push( this.model.get( 'class' ) );
		$gap.attr( 'class', classes.join( ' ' ) );

		$gap.removeAttr( 'style' );
		$gap.css( styles );

		if ( '' != data.style )
			$gap.attr( 'style', $gap.attr( 'style' ) + data.style );

		$gap.css( {
			'padding': data.gap_size + ' 0 0',
			'margin':  0,
			'height':  0,
			'minHeight': this.minHeight + 'px'
		} );

	}

} );

},{}],166:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	// Stop "no container" from being detected as empty too early
	emptyDetection: function() {
		return false;
	},

	onAfterElementRender: function(){

		// Handle height preservation between renders to avoid flicker
		if( this.model.prevHeight ) {
			this.$('.cs-empty-element').height(this.model.prevHeight);
			this.model.prevHeight = false;
		}

		_.defer(_.bind( function() {

			$map = this.$('.x-map');
			if ($map.length > 0) {
				this.model.prevHeight = $map.outerHeight();
			}

		}, this ) );

	}
});
},{}],167:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	onAfterElementRender: function() {

	}

});
},{}],168:[function(require,module,exports){
module.exports = {

	'section': require( './section' ),
	'row':     require( './row' ),
	'column':  require( './column' ),

	'accordion':     require( './accordion' ),
	'alert':         require( './alert' ),
	'card':          require( './card' ),
	'pricing-table': require( './pricing-table' ),
	'row':           require( './row' ),
	'gap':           require( './gap' ),
	'google-map':    require( './google-map' ),
	'line':          require( './line' ),
	'slider':        require( './slider' ),
	'tabs':          require( './tabs' ),

	// 3rd party
	'gravity-forms': require( './gravity-forms' ),
}
},{"./accordion":160,"./alert":161,"./card":163,"./column":164,"./gap":165,"./google-map":166,"./gravity-forms":167,"./line":169,"./pricing-table":170,"./row":171,"./section":172,"./slider":173,"./tabs":174}],169:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	template: _.template( '<hr class="x-hr">' ),
	remoteRender: false,

	onRender: function() {

		var $line, classes, visibility, customID, style, customStyle, color, height;
		$line = this.$( 'hr' );

		classes = [ 'x-hr' ];

		if ( visibility = this.model.get( 'visibility' ) ) {
			classes = _.union( classes, visibility );
		}

		classes.push( this.model.get( 'class' ) );
		$line.attr( 'class', classes.join( ' ' ) );

		customID = this.model.get( 'custom_id' );
		if ( customID ) $line.attr( 'id', customID );

		$line.removeAttr( 'style' );
		style = '';

		customStyle = this.model.get( 'style' );

		height = this.model.get( 'line_height' );
		if ( height )
			style += ' border-top-width: ' + height + ';';

		color = this.model.get( 'line_color' );
		if ( color )
			style += ' border-top-color: ' + color + ';';

		if ( customStyle ) style += ' ' + customStyle;
		$line.attr( 'style', style );

		// Replace margin with padding after this first render
		// This helps make the line clickable in the preview window
		_.defer( function() {

			$line.css({
				'paddingBottom': $line.css( 'marginBottom' ),
				'marginBottom': '0'
			});

		} );

	}

} );

},{}],170:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	onRemoveChild: function() {
		this.updateColumnClass();
	},

	onAddChild: function( child ) {
		this.updateColumnClass();
	},

	updateColumnClass: function() {

		var $table = this.$( '.x-pricing-table' );
		var classes = [ 'one-column', 'two-columns', 'three-columns', 'four-columns', 'five-columns' ];

		if ( classes[ this.collection.length - 1 ] ) {
			var columnClass = classes[ this.collection.length - 1 ];
			if ( ! $table.hasClass( columnClass ) ) {
				$table.removeClass( classes.join( ' ' ) ).addClass( columnClass );
			}
		} else {
			$table.removeClass( classes.join( ' ' ) );
		}

	}

} );

},{}],171:[function(require,module,exports){
module.exports = Cornerstone.ElementViews.Base.extend({

	remoteRender: false,
	draggable: false,

	filter: function (child, index, collection) {
    return ( child.get('_active') );
  },

  onConstructElement: function( data ) {

  	var classes, styles, visibility, textAlign, padding, margin, borderWidth, bgColor, customStyle, customID;

  	classes = [ 'x-container' ];
		styles = {};

		if ( data.inner_container ) {
			classes.push('max width')
		}

		classes = _.union( classes, cs.classMap( 'visibility', data.visibility ) );

		if ( 'none' != data.text_align ) {
			classes.push( cs.classMap( 'text_align', data.text_align ) );
		}

		if ( data.marginless_columns ) classes.push('marginless-columns');

		if ( _.isArray( padding = _.clone( data.padding ) ) ) {
			padding.pop();
			styles['padding'] = padding.join(' ');
		}

		if ( _.isArray( margin = _.clone( data.margin ) ) ) {
			margin.pop();
			styles['margin'] = margin.join(' ');
		}

		if ( _.isArray( borderWidth = _.clone( data.border_width ) ) ) {
			borderWidth.pop();
			if ( _.unique(borderWidth) != '0px' ) {
				styles['border-width'] = borderWidth.join(' ');
				styles['border-color'] = data.border_color
				styles['border-style'] = data.border_style
			}
		}

		bgColor = this.model.get('bg_color')
		styles['background-color'] = (bgColor) ? bgColor : 'transparent';


		if ( '' != data.id ) {
			this.$el.attr( 'id', data.id );
		}

		classes.push( data['class'] );
		this.$el.attr('class', classes.join(' '));
		delete classes;

		this.$el.removeAttr('style');
		this.$el.css(styles);

		if ( '' != data.style ) {
			this.$el.attr('style', this.$el.attr('style') + data.style );
		}

  }

});
},{}],172:[function(require,module,exports){
module.exports = Cornerstone.ElementViews.Base.extend({

	remoteRender: false,
	draggable: false,

	onElementInit: function() {

		this.lazyDetectColorContrast = _.debounce( _.bind( this.detectColorContrast, this ), 25 );
		this.lazyDetectImageContrast = _.debounce( _.bind( this.detectImageContrast, this ), 250 );

		this.contrast = {
			color: null,
			image: null,
			activeClass: null
		};

		this.listenTo( this.model, 'change:bg_color', function() {
			this.contrast.color = null;
		} );

		this.listenTo( this.model, 'change:bg_image', function() {
			this.contrast.image = null;
		} );
	},

	onConstructElement: function( data ) {

		var classes, styles, bgClass, padding, margin, borderWidth;
		this.$el.attr( 'id', 'x-section-' + ( this.modelIndex + 1 ) );

		classes = [ 'x-section' ];
		styles = {};
		bgClass = '';

		if ( this.contrastClass ) {
			classes.push( this.contrastClass );
		}

		classes = _.union( classes, cs.classMap( 'visibility', data.visibility ) );

		if ( 'none' != data.text_align ) {
			classes.push( cs.classMap( 'text_align', data.text_align ) );
		}

		switch ( data.bg_type ) {
			case 'video':
				bgClass = 'bg-video';
				break;
			case 'image':

				// Set Background class
				bgClass = ( data.bg_pattern_toggle ) ? 'bg-pattern' : 'bg-image';
				if ( data.parallax ) classes.push( 'parallax' );
				if ( '' != data.bg_image ) styles['backgroundImage'] = 'url("' + data.bg_image +'")';
				if ( _.isNull( this.contrast.image ) ) this.lazyDetectImageContrast( data.bg_image, data.bg_color );
				if ( ! _.isNull( this.contrast.activeClass ) ) classes.push( this.contrast.activeClass );
				styles['background-color'] = ( '' != data.bg_color ) ? data.bg_color : 'transparent';

				break;
			case 'color':

				bgClass = 'bg-color';
				if ( _.isNull( this.contrast.image ) ) this.lazyDetectColorContrast( data.bg_color );
				if ( !_.isNull( this.contrast.activeClass) ) classes.push(this.contrast.activeClass);
				styles['background-color'] = ( '' != data.bg_color ) ? data.bg_color : 'transparent';

				break;

		}

		if ( bgClass ) classes.push(bgClass);

		if ( _.isArray( padding = _.clone( data.padding ) ) ) {
			padding.pop();
			styles['padding'] = padding.join(' ');
		}

		if ( _.isArray( margin = _.clone( data.margin ) ) ) {
			margin.pop();
			styles['margin'] = margin.join(' ');
		}

		if ( _.isArray( borderWidth = _.clone( data.border_width ) ) ) {
			borderWidth.pop();
			if ( _.unique(borderWidth) != '0px' ) {
				styles['border-width'] = borderWidth.join(' ');
				styles['border-color'] = data.border_color
				styles['border-style'] = data.border_style
			}
		}


		if ( '' != data.id ) {
			this.$el.attr( 'id', data.id );
		}

		classes.push( data['class'] );
		this.$el.attr('class', classes.join(' '));
		delete classes;

		this.$el.removeAttr('style');
		this.$el.css(styles);

		if ( '' != data.style ) {
			this.$el.attr('style', this.$el.attr('style') + data.style );
		}


		// Defer things that may depend on height.
		_.defer( _.bind( function(){
			if ( this.$el.hasClass('parallax') ) {
		    if ( csModernizr.touchevents ) {
		      this.$el.css('background-attachment', 'scroll');
		    } else {
		      if ( this.$el.hasClass('bg-image')   ) speed = 0.1;
		      if ( this.$el.hasClass('bg-pattern') ) speed = 0.3;
		      if ( speed ) this.$el.parallaxContentBand('50%', speed);
		    }
		  }

		  if ( this.$el.hasClass('bg-video') ) {
		  	this.$el.css({
		  		'background-image': 'url("' + data.bg_video_poster +'")',
		  		'background-color': 'white',
		  		'background-size':  'cover'
		  	});
		  }
	  }, this ) );
	},

	detectImageContrast: function( image, color ) {
		_.defer( _.bind( function(){

			if ( !image || image == '' ) {
				this.detectColorContrast( color );
				return;
			}

      window.RGBaster.colors( image, {
			  success: _.bind( function(payload) {
			    this.setContrastClass( payload.dominant );
			  }, this )
			});

    }, this ) );
	},

	detectColorContrast: function( color ) {
		if (!color || color == '')
			color = '#ffffff';
		this.setContrastClass( color );
	},

	setContrastClass:function( color ) {
		var source = new Cornerstone.Vendor.Color( color );
		var isDark = ( source.getDistanceLuminosityFrom( new Cornerstone.Vendor.Color( '#fff' ) ) > 10.5 );
		this.contrast.activeClass = ( isDark ) ? 'cs-bg-dark' : null;
		this.$el.toggleClass( 'cs-bg-dark', isDark );
	}

})
},{}],173:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({
	emptyDetection: function() {
		// Prevent empty detection
	}
} );
},{}],174:[function(require,module,exports){
module.exports =  Cornerstone.ElementViews.Base.extend({

	onClickBeforeInspect: function( e ) {
		$target = jQuery(e.target);
		if ( $target.attr('data-toggle') == 'tab' ) {
			jQuery.fn.tab.call( $target, 'show');
		}
	},

});
},{}],175:[function(require,module,exports){
module.exports = CS.Mn.CollectionView.extend({
	childView: require('./settings-section'),
	events: {
		'click .cs-pane-section-toggle': 'toggle'
	},
	toggle: function( e ) {

		var $target = this.$( e.currentTarget );
		var $section = $target.next('.cs-pane-section')

		if ( $target.hasClass('active') ) {
      $target.removeClass('active');
      $section.slideUp('fast');
      cs.navigate.trigger( 'scrollbar:update' );
      return;
    }


  	this.$('.cs-pane-section-toggle').removeClass('active');
    $target.addClass('active');
    cs.navigate.trigger( 'scrollbar:update' );
    $section.slideDown('fast');
    this.$('.cs-pane-section').not('.constant').not($section).slideUp('fast');

  }
});
},{"./settings-section":177}],176:[function(require,module,exports){
var ControlListView = require('../controls/control-collection');
var SettingsCollectionView = require('./settings-collection');
module.exports = CS.Mn.LayoutView.extend({

	template: 'settings/content',

	regions: {
    Controls: '#controls',
    Sections: '#sections'
  },

  initialize: function() {

  },

  onBeforeShow: function() {

  	this.Controls.show( new ControlListView( {
  		collection: cs.component('settings').getPrimaryControls(),
  	}));

  	this.Sections.show( new SettingsCollectionView( {
  		collection: cs.component('settings').getSettings()
  	}));

  }

});
},{"../controls/control-collection":103,"./settings-collection":175}],177:[function(require,module,exports){
module.exports = CS.Mn.CompositeView.extend({
	template: 'settings/section',
	className: 'cs-settings-section',
	childViewContainer: 'ul.cs-controls',
	getChildView: function( item ) { return cs.controlLookup(item.get('type')); },

	initialize: function() {
		this.collection = this.model.inspect.controls;
	},

	onRender: function() {
		if ( this.collection.isEmpty() )
			this.$el.addClass('empty');
	},

	serializeData: function() {

		return _.extend( CS.Mn.CompositeView.prototype.serializeData.apply(this,arguments), {
			_section_title: this.model.section.get('ui').title
		});

	}
});
},{}],178:[function(require,module,exports){
var ViewBasePane = require('../main/base-pane');
var SettingsContentView = require('./settings-content');
var ControlListView = require('../controls/control-collection');
module.exports = ViewBasePane.extend({

	name: 'settings',

  initialize: function() {
  	this.listenTo( cs.global, 'settings:ready', this.onShowContent );
  },

  onShowContent: function() {
  	this.Content.show( ( cs.data.request( 'settings:ready' ) ) ? new SettingsContentView() : this.getLoadingView() );
  },

  getLoadingView: function() {
    return new CS.Mn.ItemView( {
      tagName: 'ul',
      className: 'cs-controls empty',
      template: _.template("<li class=\"cs-control-empty\"><span class=\"title\"><%= l18n('settings-loading') %></span></li>")
    } );
  },

  onOpenSubItem: function() {

  	this.Sub.show( new ControlListView( {
  		collection: cs.component('inspector').getSecondaryControls(),
  	}));

  }

});
},{"../controls/control-collection":103,"../main/base-pane":153,"./settings-content":176}],179:[function(require,module,exports){
module.exports = CS.Mn.CompositeView.extend( {

	remoteRender: true,
	template: 'main/skeleton-item',
	childViewContainer: '.cs-skeleton-container',

	duplicatable: function() {
		var flags = this.model.definition.get( 'flags' );
		return ( flags && !flags.child );
	},
	deletable: function() {
		return _.result( this, 'duplicatable' );
	},
	eraseable: false,
	manageable: false,
	collapsible: false,

	className: function() {
		var classes = ['cs-skeleton-item'];

		var type = this.model.get('_type');

		if ( _.contains( ['section', 'row', 'column'], type ) ) {
			classes.push( 'layout' );
		} else {
			classes.push( 'element' );
		}

		classes.push( type )

		var flags = this.model.definition.get( 'flags' );

		if ( flags && flags.child ) {
			classes.push( 'is-child' );
		}

		var skeletonClasses = _.result( this, 'skeletonClasses' );

		if ( _.isArray( skeletonClasses ) )
			classes = classes.concat( skeletonClasses );

		return classes.join(' ');
	},

	attributes: function() {
		return {
			'data-element-type': this.model.get('_type')
		}
	},

	events: function() {

		var events = {
			'click .cs-skeleton-controls button': 'clickControl'
		};

		var inspectable = _.result( this, 'inspectable' )


		if ( inspectable || _.isUndefined( inspectable ) ) {

			events = _.extend( events, {
				'click > .cs-skeleton-handle': '_click',
				'mouseover > .cs-skeleton-handle': '_observeStart',
				'mouseout > .cs-skeleton-handle': '_observeEnd'
			} );

		}

		// if ( _.result( this, 'collapsible' ) ) {

		// 	events = _.extend( events, {
		// 		'click .cs-icon.collapse': 'collapse',
		// 		// 'mouseover .cs-icon.collapse': 'collapseHover',
		// 	} );

		// }

		var draggable = _.result( this, 'draggable' )
		if ( draggable || _.isUndefined( draggable ) ) {

			events = _.extend( events, {
				'dragula:lift': '_dragLift',
				'dragula:cancel': '_dragEnd',
			} );

		}

		var receiver = _.result( this, 'receiver' )
		if ( receiver || _.isUndefined( receiver ) ) {

			events = _.extend( events, {
				'dragula:receive': '_receiveElement',
				'dragula:over': '_dragOver',
				'dragula:out': '_dragOut',
			} );

		}

		return _.extend( events, _.result( this, 'elementEvents' ) || {} );
	},

	getChildView: function( item ) {
		return cs.component('view-loader').skeletonLookup( item.get('_type') );
	},

	constructor: function( options ) {

		CS.Mn.CompositeView.apply(this, arguments);

		this._repaint = _.debounce( _.bind( function() {
			try {
				this.render()
			} catch (e) {
				if ( e.name == 'ViewDestroyedError' ) return;
				console.log('Cornerstone Render Exception', e );
			}
		}, this ), 2 );

		this.collection = this.model.elements;
		this.listenTo( this.collection, 'sort', this.render );
		this.listenTo( this.collection, 'remove', this.render );

		this.listenTo( cs.events, 'skeleton:hover:toggle', this.hoverToggle );

		this.listenTo( cs.events, 'skeleton:dragging', function( state ) {
			if ( state ) return;
			this.$el.removeClass( 'cs-receiving' );
		})

		if ( this.model.collection )
			this.modelIndex = this.model.collection.indexOf( this.model );

	},

	serializeData: function() {

		var text = '';
		var extra = '';
		var controls = [];
		var type = this.model.get('_type');

		switch ( type ) {
      case 'section':
        text = cs.l18n('section-format').replace('%s', this.model.get('title') );
        break;
      case 'row':
        text = cs.l18n('row-numeric').replace('%s', this.model.getIndex() + 1 );
        break;
      case 'column':
        text = cs.l18n('column-format').replace('%s', this.model.get('size') );
        break;
      default:
        extra = cs.elementIcon( type );
      	text = this.model.definition.get('ui').title;
      	break;
    }

    if ( _.result( this, 'manageable' ) ) {
    	controls.push({ action: 'managelayout', icon: 'bars', tooltip: cs.l18n('tooltip-manage-layout') });
    }

    if ( _.result( this, 'duplicatable' ) ) {
    	controls.push({ action: 'duplicate', icon: 'copy', tooltip: cs.l18n('tooltip-copy') });
    }

    if ( _.result( this, 'eraseable' ) ) {
    	controls.push({ action: 'erase', icon: 'eraser', tooltip: cs.l18n('tooltip-erase') });
    }

    if ( _.result( this, 'deletable' ) ) {
    	controls.push({ action: 'delete', icon: 'trash-o', tooltip: cs.l18n('tooltip-delete')
    	});
    }

    var userControls = _.clone( _.result( this, 'controls' ) );

    if ( _.result( this, 'collapsible' ) ) {
    	controls.push({ action: 'collapse', icon: 'caret-down', iconAlt: 'caret-up', persist: true });
    }

    this.tooltipText = text;

		return _.extend( CS.Mn.CompositeView.prototype.serializeData.apply( this, arguments ), {
			title: extra + text,
			controls: controls
		} );
	},

  _click: function( e ) {
		this.triggerMethod('click:before:inspect', e );
		e.stopPropagation();
		cs.global.trigger( 'inspect', this.model )
  },

  _dragLift: function( e ) {
  	e.stopPropagation();
  	cs.incoming = this.model;
  },

  _dragEnd: function( e ) {
  	cs.incoming = false;
  },

  _dragOver: function( e ) {
  	e.stopPropagation();
		this.$el.toggleClass( 'cs-receiving', true );
	},

	_dragOut: function( e ) {
		this.$el.toggleClass( 'cs-receiving', false );
	},

	_observeStart: function( e ) {
		e.stopPropagation();
		this.model.trigger( 'observe:start' );
		cs.events.trigger( 'skeleton:hover', true, this );
  },

  _observeEnd: function( e ) {
  	this.model.trigger('observe:end');
  	cs.events.trigger( 'skeleton:hover', false, this );
  },

  _receiveElement: function( e, el, source, sibling ) {
		e.stopPropagation();
		this.triggerMethod( 'receive:element', e, el, source, sibling );
		_.defer( _.bind( this._placeElement, this ), ( sibling ) ? Backbone.$(sibling).index() - 1 : this.collection.length );
	},

	_placeElement: function( position ) {

		if ( !cs.incoming ) return;

		var data = ( !_.isFunction( cs.incoming.toJSON ) ) ? cs.incoming.data : cs.incoming.toJSON();
		var newModel = this.model.elements.create( data, _.clone( cs.incoming.options ), {
			position: position,
			replace: cs.incoming,
			after: function( model ) {
				cs.incoming = false;
			}
		} );

	},

	onRender: function() {
		this.$el.toggleClass( 'collapsed', !!this.model.getMeta( 'skeleton_collapsed' ) );
	},

	clickControl: function( e ) {
		e.stopPropagation();
		var action = this.$(e.currentTarget).data( 'action' );
		this.triggerMethod('control:' + action );
	},

	onControlManagelayout: function() {
		if ( !_.result( this, 'manageable' ) ) return;
		cs.events.trigger( 'inspect:layout', this.model, { navigate: true } );
	},

	onControlDuplicate: function() {
		if ( !_.result( this, 'duplicatable' ) ) return;
		cs.global.trigger( 'element:duplicate', this.model );
	},

	onControlDelete: function() {
		if ( !_.result( this, 'deletable' ) ) return;
		cs.global.trigger( 'element:delete', this.model );
	},

	onControlErase: function() {
		if ( !_.result( this, 'eraseable' ) ) return;
		cs.global.trigger( 'element:erase', this.model );
	},

	onControlCollapse: function() {
		if ( !_.result( this, 'collapsible' ) ) return;

		var newState = !this.model.getMeta( 'skeleton_collapsed' )
		var $parent = this.$el.toggleClass( 'collapsed', newState );
		this.model.setMeta( 'skeleton_collapsed', newState );

	},

	collapseHover: function( e ) {
		e.stopPropagation();
	},

	hoverToggle: function( cid ) {
		this.$el.toggleClass( 'hover', cid == this.cid );
	}

} );
},{}],180:[function(require,module,exports){
module.exports = Cornerstone.SkeletonViews.Base.extend({

	receiver: true,

	manageable: true,
	duplicatable: false,
	deletable: false,
	eraseable: true,

	elementEvents: {
		'dragula:drop': 'updatePosition',
	},

	skeletonClasses: function() {
		return [ 'size-' + this.model.get('size').replace('/','-') ];
	},

	updatePosition: function( target, source, sibling ) {
    this.triggerMethod( 'update:position', target, source, sibling );
  },

  _dragLift: function( e ) {
  	e.stopPropagation();
  },

});
},{}],181:[function(require,module,exports){
module.exports = {
	'section' : require('./section'),
	'row'     : require('./row'),
	'column'  : require('./column'),
}
},{"./column":180,"./row":182,"./section":183}],182:[function(require,module,exports){
module.exports = Cornerstone.SkeletonViews.Base.extend({

	receiver: false,

	manageable: true,
	collapsible: true,

	childViewContainer: '.cs-skeleton-container-inner',

	elementEvents: {
		'recieve:element' : '_receiveElement',
		'dragula:lift:child' : 'liftColumn'
	},

	filter: function (child, index, collection) {
    return ( child.get('_active') );
  },

  initialize: function() {
  	this.equalize = _.debounce( _.bind( this._equalize, this ), 1 )
  },

  onRender: function() {
  	this.setupDrake();
  	this._equalize();
  	this.equalize();
  },

  onChildviewUpdatePosition: function( child, target, source, sibling ) {

  	this.equalize();

  	// Wait until Dragula removes the mirror image.
  	_.defer( _.bind( function(){
  		this.triggerMethod( 'item:before:position:updated', child );
    	this.collection.trigger( 'update:position', child.model, child.$el.index() );
    	this.triggerMethod( 'item:position:updated', child );
  	}, this ) );

  },

  setupDrake: function() {

  	if ( this.drake ) {
  		this.drake.destroy();
  	}

  	var $container = this.$('.cs-skeleton-container-inner');
  	var row = this;

  	this.drake = Cornerstone.Vendor.dragula( {
  		offset: function( offset, e, item ) {
  			offset.x = Backbone.$(item).width() / 2;
  			return offset;
  		},
  		accepts: function( el, target, source, sibling ) {
				return Backbone.$(el).is('.cs-skeleton-item.column');
			},
  		moves: function (el, source, handle, sibling) {
  			return Backbone.$(handle).is('.cs-skeleton-item.column > .cs-skeleton-handle > .cs-skeleton-title') && Backbone.$(el).siblings().length > 0;
		  },
  		direction: 'horizontal',
		  revertOnSpill: false,
		  mirrorContainer: Backbone.$('#before')[0],
		  containers: [$container[0]]
  	} );

  	var handlers = require('../../utility/dragula-handlers');

  	this.drake.on('drag', handlers.skeletonStart );
  	this.drake.on('dragend', handlers.skeletonEnd );

		this.drake.on('drop', handlers.drop );

  },

  onChildviewReceiveElement: function() {
  	this._equalize();
  },

  _equalize: function( ) {

  	this.$el.equalize({
  		equalize: 'outerHeight',
			children: '.cs-skeleton-container-column',
			reset: true,
			offset: this.$('.cs-skeleton-handle' ).outerHeight() - 1
		});

  },

  _observeStart: function( e ) {
		e.stopPropagation();
		this.model.trigger( 'observe:start' );
		if ( !this.$el.hasClass( 'cs-receiving' ) )
			cs.events.trigger( 'skeleton:hover', true, this );
  },

  liftColumn: function() {
  	this.$el.addClass( 'cs-receiving' );
  },

  onDestroy: function() {
  	this.drake.destroy();
  },

  onChildviewRender: function() {
  	this._equalize(); // leading and trailing edge
  	this.equalize();
  },

  _receiveElement: function( e ) {
  	e.stopPropagation();
  }

});
},{"../../utility/dragula-handlers":93}],183:[function(require,module,exports){
module.exports = Cornerstone.SkeletonViews.Base.extend({
	receiver: true,
	manageable: true,
	collapsible: true,

	initialize: function() {
		this.listenTo( this.model, 'change:title', this.updateTitle );
	},

	updateTitle: function( model, title ) {
		this.$( '> .cs-skeleton-handle > .cs-skeleton-title' ).html( cs.l18n( 'section-format' ).replace( '%s', title ) );
	}

});

},{}],184:[function(require,module,exports){
'use strict';

var cache = {};
var start = '(?:^|\\s)';
var end = '(?:\\s|$)';

function lookupClass (className) {
  var cached = cache[className];
  if (cached) {
    cached.lastIndex = 0;
  } else {
    cache[className] = cached = new RegExp(start + className + end, 'g');
  }
  return cached;
}

function addClass (el, className) {
  var current = el.className;
  if (!current.length) {
    el.className = className;
  } else if (!lookupClass(className).test(current)) {
    el.className += ' ' + className;
  }
}

function rmClass (el, className) {
  el.className = el.className.replace(lookupClass(className), ' ').trim();
}

module.exports = {
  add: addClass,
  rm: rmClass
};

},{}],185:[function(require,module,exports){
(function (global){
'use strict';

var emitter = require('contra/emitter');
var crossvent = require('crossvent');
var classes = require('./classes');
var doc = document;
var documentElement = doc.documentElement;
var body = doc.body;

function dragula (initialContainers, options) {
  var len = arguments.length;
  if (len === 1 && Array.isArray(initialContainers) === false) {
    options = initialContainers;
    initialContainers = [];
  }
  var _mirror; // mirror image
  var _source; // source container
  var _item; // item being dragged
  var _offsetX; // reference x
  var _offsetY; // reference y
  var _moveX; // reference move x
  var _moveY; // reference move y
  var _initialSibling; // reference sibling when grabbed
  var _currentSibling; // reference sibling now
  var _copy; // item used for copying
  var _renderTimer; // timer for setTimeout renderMirrorImage
  var _lastDropTarget = null; // last container item was over
  var _grabbed; // holds mousedown context until first mousemove

  var o = options || {};
  if (o.moves === void 0) { o.moves = always; }
  if (o.accepts === void 0) { o.accepts = always; }
  if (o.invalid === void 0) { o.invalid = invalidTarget; }
  if (o.containers === void 0) { o.containers = initialContainers || []; }
  if (o.isContainer === void 0) { o.isContainer = never; }
  if (o.copy === void 0) { o.copy = false; }
  if (o.copySortSource === void 0) { o.copySortSource = false; }
  if (o.revertOnSpill === void 0) { o.revertOnSpill = false; }
  if (o.removeOnSpill === void 0) { o.removeOnSpill = false; }
  if (o.direction === void 0) { o.direction = 'vertical'; }
  if (o.ignoreInputTextSelection === void 0) { o.ignoreInputTextSelection = true; }
  if (o.mirrorContainer === void 0) { o.mirrorContainer = body; }
  if (o.offset === void 0) { o.offset = thru; }

  var drake = emitter({
    containers: o.containers,
    start: manualStart,
    end: end,
    cancel: cancel,
    remove: remove,
    destroy: destroy,
    dragging: false
  });

  if (o.removeOnSpill === true) {
    drake.on('over', spillOver).on('out', spillOut);
  }

  events();

  return drake;

  function isContainer (el) {
    return drake.containers.indexOf(el) !== -1 || o.isContainer(el);
  }

  function events (remove) {
    var op = remove ? 'remove' : 'add';
    touchy(documentElement, op, 'mousedown', grab);
    touchy(documentElement, op, 'mouseup', release);
  }

  function eventualMovements (remove) {
    var op = remove ? 'remove' : 'add';
    touchy(documentElement, op, 'mousemove', startBecauseMouseMoved);
  }

  function movements (remove) {
    var op = remove ? 'remove' : 'add';
    touchy(documentElement, op, 'selectstart', preventGrabbed); // IE8
    touchy(documentElement, op, 'click', preventGrabbed);
  }

  function destroy () {
    events(true);
    release({});
  }

  function preventGrabbed (e) {
    if (_grabbed) {
      e.preventDefault();
    }
  }

  function grab (e) {
    _moveX = e.clientX;
    _moveY = e.clientY;

    var ignore = whichMouseButton(e) !== 1 || e.metaKey || e.ctrlKey;
    if (ignore) {
      return; // we only care about honest-to-god left clicks and touch events
    }
    var item = e.target;
    var context = canStart(item);
    if (!context) {
      return;
    }
    _grabbed = context;
    eventualMovements();
    if (e.type === 'mousedown') {
      if (isInput(item)) { // see also: https://github.com/bevacqua/dragula/issues/208
        item.focus(); // fixes https://github.com/bevacqua/dragula/issues/176
      } else {
        e.preventDefault(); // fixes https://github.com/bevacqua/dragula/issues/155
      }
    }
  }

  function startBecauseMouseMoved (e) {
    if (!_grabbed) {
      return;
    }
    if (whichMouseButton(e) === 0) {
      release({});
      return; // when text is selected on an input and then dragged, mouseup doesn't fire. this is our only hope
    }
    // truthy check fixes #239, equality fixes #207
    if (e.clientX !== void 0 && e.clientX === _moveX && e.clientY !== void 0 && e.clientY === _moveY) {
      return;
    }
    if (o.ignoreInputTextSelection) {
      var clientX = getCoord('clientX', e);
      var clientY = getCoord('clientY', e);
      var elementBehindCursor = doc.elementFromPoint(clientX, clientY);
      if (isInput(elementBehindCursor)) {
        return;
      }
    }

    var grabbed = _grabbed; // call to end() unsets _grabbed
    eventualMovements(true);
    movements();
    end();
    start(grabbed);


    var offset = getOffset(_item);
    var calculatedOffset = o.offset( {
      x: getCoord('pageX', e) - offset.left,
      y: getCoord('pageY', e) - offset.top
    }, e, _item );

    _offsetX = calculatedOffset.x;
    _offsetY = calculatedOffset.y;

    classes.add(_copy || _item, 'gu-transit');
    renderMirrorImage();
    drag(e);
  }

  function canStart (item) {
    if (drake.dragging && _mirror) {
      return;
    }
    if (isContainer(item)) {
      return; // don't drag container itself
    }
    var handle = item;
    while (getParent(item) && isContainer(getParent(item)) === false) {
      if (o.invalid(item, handle)) {
        return;
      }
      item = getParent(item); // drag target should be a top element
      if (!item) {
        return;
      }
    }
    var source = getParent(item);
    if (!source) {
      return;
    }
    if (o.invalid(item, handle)) {
      return;
    }

    var movable = o.moves(item, source, handle, nextEl(item));
    if (!movable) {
      return;
    }

    return {
      item: item,
      source: source
    };
  }

  function manualStart (item) {
    var context = canStart(item);
    if (context) {
      start(context);
    }
  }

  function start (context) {
    if (isCopy(context.item, context.source)) {
      _copy = context.item.cloneNode(true);
      drake.emit('cloned', _copy, context.item, 'copy');
    }

    _source = context.source;
    _item = context.item;
    _initialSibling = _currentSibling = nextEl(context.item);

    drake.dragging = true;
    drake.emit('drag', _item, _source);
  }

  function invalidTarget () {
    return false;
  }

  function end () {
    if (!drake.dragging) {
      return;
    }
    var item = _copy || _item;
    drop(item, getParent(item));
  }

  function ungrab () {
    _grabbed = false;
    eventualMovements(true);
    movements(true);
  }

  function release (e) {
    ungrab();

    if (!drake.dragging) {
      return;
    }
    var item = _copy || _item;
    var clientX = getCoord('clientX', e);
    var clientY = getCoord('clientY', e);
    var elementBehindCursor = getElementBehindPoint(_mirror, clientX, clientY);
    var dropTarget = findDropTarget(elementBehindCursor, clientX, clientY);
    if (dropTarget && ((_copy && o.copySortSource) || (!_copy || dropTarget !== _source))) {
      drop(item, dropTarget);
    } else if (o.removeOnSpill) {
      remove();
    } else {
      cancel();
    }
  }

  function drop (item, target) {
    var parent = getParent(item);
    if (_copy && o.copySortSource && target === _source) {
      parent.removeChild(_item);
    }
    if (isInitialPlacement(target)) {
      drake.emit('cancel', item, _source, _source);
    } else {
      drake.emit('drop', item, target, _source, _currentSibling);
    }
    cleanup();
  }

  function remove () {
    if (!drake.dragging) {
      return;
    }
    var item = _copy || _item;
    var parent = getParent(item);
    if (parent) {
      parent.removeChild(item);
    }
    drake.emit(_copy ? 'cancel' : 'remove', item, parent, _source);
    cleanup();
  }

  function cancel (revert) {
    if (!drake.dragging) {
      return;
    }
    var reverts = arguments.length > 0 ? revert : o.revertOnSpill;
    var item = _copy || _item;
    var parent = getParent(item);
    if (parent === _source && _copy) {
      parent.removeChild(_copy);
    }
    var initial = isInitialPlacement(parent);
    if (initial === false && !_copy && reverts) {
      _source.insertBefore(item, _initialSibling);
    }
    if (initial || reverts) {
      drake.emit('cancel', item, _source, _source);
    } else {
      drake.emit('drop', item, parent, _source, _currentSibling);
    }
    cleanup();
  }

  function cleanup () {
    var item = _copy || _item;
    ungrab();
    removeMirrorImage();
    if (item) {
      classes.rm(item, 'gu-transit');
    }
    if (_renderTimer) {
      clearTimeout(_renderTimer);
    }
    drake.dragging = false;
    if (_lastDropTarget) {
      drake.emit('out', item, _lastDropTarget, _source);
    }
    drake.emit('dragend', item);
    _source = _item = _copy = _initialSibling = _currentSibling = _renderTimer = _lastDropTarget = null;
  }

  function isInitialPlacement (target, s) {
    var sibling;
    if (s !== void 0) {
      sibling = s;
    } else if (_mirror) {
      sibling = _currentSibling;
    } else {
      sibling = nextEl(_copy || _item);
    }
    return target === _source && sibling === _initialSibling;
  }

  function findDropTarget (elementBehindCursor, clientX, clientY) {
    var target = elementBehindCursor;
    while (target && !accepted()) {
      target = getParent(target);
    }
    return target;

    function accepted () {
      var droppable = isContainer(target);
      if (droppable === false) {
        return false;
      }

      var immediate = getImmediateChild(target, elementBehindCursor);
      var reference = getReference(target, immediate, clientX, clientY);
      var initial = isInitialPlacement(target, reference);
      if (initial) {
        return true; // should always be able to drop it right back where it was
      }
      return o.accepts(_item, target, _source, reference);
    }
  }

  function drag (e) {
    if (!_mirror) {
      return;
    }
    e.preventDefault();

    var clientX = getCoord('clientX', e);
    var clientY = getCoord('clientY', e);
    var x = clientX - _offsetX;
    var y = clientY - _offsetY;

    _mirror.style.left = x + 'px';
    _mirror.style.top = y + 'px';

    var item = _copy || _item;
    var elementBehindCursor = getElementBehindPoint(_mirror, clientX, clientY);
    var dropTarget = findDropTarget(elementBehindCursor, clientX, clientY);
    var changed = dropTarget !== null && dropTarget !== _lastDropTarget;
    if (changed || dropTarget === null) {
      out();
      _lastDropTarget = dropTarget;
      over();
    }
    var parent = getParent(item);
    if (dropTarget === _source && _copy && !o.copySortSource) {
      if (parent) {
        parent.removeChild(item);
      }
      return;
    }
    var reference;
    var immediate = getImmediateChild(dropTarget, elementBehindCursor);
    if (immediate !== null) {
      reference = getReference(dropTarget, immediate, clientX, clientY);
    } else if (o.revertOnSpill === true && !_copy) {
      reference = _initialSibling;
      dropTarget = _source;
    } else {
      if (_copy && parent) {
        parent.removeChild(item);
      }
      return;
    }
    if (
      reference === null ||
      reference !== item &&
      reference !== nextEl(item) &&
      reference !== _currentSibling
    ) {
      _currentSibling = reference;
      dropTarget.insertBefore(item, reference);
      drake.emit('shadow', item, dropTarget, _source);
    }
    function moved (type) { drake.emit(type, item, _lastDropTarget, _source); }
    function over () { if (changed) { moved('over'); } }
    function out () { if (_lastDropTarget) { moved('out'); } }
  }

  function spillOver (el) {
    classes.rm(el, 'gu-hide');
  }

  function spillOut (el) {
    if (drake.dragging) { classes.add(el, 'gu-hide'); }
  }

  function renderMirrorImage () {
    if (_mirror) {
      return;
    }
    var rect = _item.getBoundingClientRect();
    _mirror = _item.cloneNode(true);
    _mirror.style.width = getRectWidth(rect) + 'px';
    _mirror.style.height = getRectHeight(rect) + 'px';
    classes.rm(_mirror, 'gu-transit');
    classes.add(_mirror, 'gu-mirror');
    o.mirrorContainer.appendChild(_mirror);
    touchy(documentElement, 'add', 'mousemove', drag);
    classes.add(o.mirrorContainer, 'gu-unselectable');
    drake.emit('cloned', _mirror, _item, 'mirror');
  }

  function removeMirrorImage () {
    if (_mirror) {
      classes.rm(o.mirrorContainer, 'gu-unselectable');
      touchy(documentElement, 'remove', 'mousemove', drag);
      getParent(_mirror).removeChild(_mirror);
      _mirror = null;
    }
  }

  function getImmediateChild (dropTarget, target) {
    var immediate = target;
    while (immediate !== dropTarget && getParent(immediate) !== dropTarget) {
      immediate = getParent(immediate);
    }
    if (immediate === documentElement) {
      return null;
    }
    return immediate;
  }

  function getReference (dropTarget, target, x, y) {
    var horizontal = o.direction === 'horizontal';
    var reference = target !== dropTarget ? inside() : outside();
    return reference;

    function outside () { // slower, but able to figure out any position
      var len = dropTarget.children.length;
      var i;
      var el;
      var rect;
      for (i = 0; i < len; i++) {
        el = dropTarget.children[i];
        rect = el.getBoundingClientRect();
        if (horizontal && rect.left > x) { return el; }
        if (!horizontal && rect.top > y) { return el; }
      }
      return null;
    }

    function inside () { // faster, but only available if dropped inside a child element
      var rect = target.getBoundingClientRect();
      if (horizontal) {
        return resolve(x > rect.left + getRectWidth(rect) / 2);
      }
      return resolve(y > rect.top + getRectHeight(rect) / 2);
    }

    function resolve (after) {
      return after ? nextEl(target) : target;
    }
  }

  function isCopy (item, container) {
    return typeof o.copy === 'boolean' ? o.copy : o.copy(item, container);
  }
}

function touchy (el, op, type, fn) {
  var touch = {
    mouseup: 'touchend',
    mousedown: 'touchstart',
    mousemove: 'touchmove'
  };
  var microsoft = {
    mouseup: 'MSPointerUp',
    mousedown: 'MSPointerDown',
    mousemove: 'MSPointerMove'
  };
  if (global.navigator.msPointerEnabled) {
    crossvent[op](el, microsoft[type], fn);
  }
  crossvent[op](el, touch[type], fn);
  crossvent[op](el, type, fn);
}

function whichMouseButton (e) {
  if (e.touches !== void 0) { return e.touches.length; }
  if (e.buttons !== void 0) { return e.buttons; }
  if (e.which !== void 0) { return e.which; }
  var button = e.button;
  if (button !== void 0) { // see https://github.com/jquery/jquery/blob/99e8ff1baa7ae341e94bb89c3e84570c7c3ad9ea/src/event.js#L573-L575
    return button & 1 ? 1 : button & 2 ? 3 : (button & 4 ? 2 : 0);
  }
}

function getOffset (el) {
  var rect = el.getBoundingClientRect();
  return {
    left: rect.left + getScroll('scrollLeft', 'pageXOffset'),
    top: rect.top + getScroll('scrollTop', 'pageYOffset')
  };
}

function getScroll (scrollProp, offsetProp) {
  if (typeof global[offsetProp] !== 'undefined') {
    return global[offsetProp];
  }
  if (documentElement.clientHeight) {
    return documentElement[scrollProp];
  }
  return body[scrollProp];
}

function getElementBehindPoint (point, x, y) {
  var p = point || {};
  var state = p.className;
  var el;
  p.className += ' gu-hide';
  el = doc.elementFromPoint(x, y);
  p.className = state;
  return el;
}

function never () { return false; }
function always () { return true; }
function thru (val) { return val; }
function getRectWidth (rect) { return rect.width || (rect.right - rect.left); }
function getRectHeight (rect) { return rect.height || (rect.bottom - rect.top); }
function getParent (el) { return el.parentNode === doc ? null : el.parentNode; }
function isInput (el) { return el.tagName === 'INPUT' || el.tagName === 'TEXTAREA' || el.tagName === 'SELECT'; }

function nextEl (el) {
  return el.nextElementSibling || manually();
  function manually () {
    var sibling = el;
    do {
      sibling = sibling.nextSibling;
    } while (sibling && sibling.nodeType !== 1);
    return sibling;
  }
}

function getEventHost (e) {
  // on touchend event, we have to use `e.changedTouches`
  // see http://stackoverflow.com/questions/7192563/touchend-event-properties
  // see https://github.com/bevacqua/dragula/issues/34
  if (e.targetTouches && e.targetTouches.length) {
    return e.targetTouches[0];
  }
  if (e.changedTouches && e.changedTouches.length) {
    return e.changedTouches[0];
  }
  return e;
}

function getCoord (coord, e) {
  var host = getEventHost(e);
  var missMap = {
    pageX: 'clientX', // IE8
    pageY: 'clientY' // IE8
  };
  if (coord in missMap && !(coord in host) && missMap[coord] in host) {
    coord = missMap[coord];
  }
  return host[coord];
}

module.exports = dragula;

}).call(this,window || {})

},{"./classes":184,"contra/emitter":193,"crossvent":197}],186:[function(require,module,exports){
/*!
 * HTMLHint v0.9.13
 * https://github.com/yaniswang/HTMLHint
 *
 * (c) 2014-2015 Yanis Wang <yanis.wang@gmail.com>.
 * MIT Licensed
 */
var HTMLHint=function(e){function t(e,t){return Array(e+1).join(t||" ")}var a={};return a.version="0.9.13",a.release="20151030",a.rules={},a.defaultRuleset={"tagname-lowercase":!0,"attr-lowercase":!0,"attr-value-double-quotes":!0,"doctype-first":!0,"tag-pair":!0,"spec-char-escape":!0,"id-unique":!0,"src-not-empty":!0,"attr-no-duplication":!0,"title-require":!0},a.addRule=function(e){a.rules[e.id]=e},a.verify=function(t,n){t=t.replace(/^\s*<!--\s*htmlhint\s+([^\r\n]+?)\s*-->/i,function(t,a){return n===e&&(n={}),a.replace(/(?:^|,)\s*([^:,]+)\s*(?:\:\s*([^,\s]+))?/g,function(t,a,i){"false"===i?i=!1:"true"===i&&(i=!0),n[a]=i===e?!0:i}),""}),(n===e||0===Object.keys(n).length)&&(n=a.defaultRuleset);var i,r=new HTMLParser,s=new a.Reporter(t,n),o=a.rules;for(var l in n)i=o[l],i!==e&&n[l]!==!1&&i.init(r,s,n[l]);return r.parse(t),s.messages},a.format=function(e,a){a=a||{};var n=[],i={white:"",grey:"",red:"",reset:""};a.colors&&(i.white="[37m",i.grey="[90m",i.red="[31m",i.reset="[39m");var r=a.indent||0;return e.forEach(function(e){var a=40,s=a+20,o=e.evidence,l=e.line,u=e.col,d=o.length,c=u>a+1?u-a:1,f=o.length>u+s?u+s:d;a+1>u&&(f+=a-u+1),o=o.replace(/\t/g," ").substring(c-1,f),c>1&&(o="..."+o,c-=3),d>f&&(o+="..."),n.push(i.white+t(r)+"L"+l+" |"+i.grey+o+i.reset);var g=u-c,h=o.substring(0,g).match(/[^\u0000-\u00ff]/g);null!==h&&(g+=h.length),n.push(i.white+t(r)+t((l+"").length+3+g)+"^ "+i.red+e.message+" ("+e.rule.id+")"+i.reset)}),n},a}();"object"==typeof exports&&exports&&(exports.HTMLHint=HTMLHint),function(e){var t=function(){var e=this;e._init.apply(e,arguments)};t.prototype={_init:function(e,t){var a=this;a.html=e,a.lines=e.split(/\r?\n/);var n=e.match(/\r?\n/);a.brLen=null!==n?n[0].length:0,a.ruleset=t,a.messages=[]},error:function(e,t,a,n,i){this.report("error",e,t,a,n,i)},warn:function(e,t,a,n,i){this.report("warning",e,t,a,n,i)},info:function(e,t,a,n,i){this.report("info",e,t,a,n,i)},report:function(e,t,a,n,i,r){for(var s,o,l=this,u=l.lines,d=l.brLen,c=a-1,f=u.length;f>c&&(s=u[c],o=s.length,n>o&&f>a);c++)a++,n-=o,1!==n&&(n-=d);l.messages.push({type:e,message:t,raw:r,evidence:s,line:a,col:n,rule:{id:i.id,description:i.description,link:"https://github.com/yaniswang/HTMLHint/wiki/"+i.id}})}},e.Reporter=t}(HTMLHint);var HTMLParser=function(e){var t=function(){var e=this;e._init.apply(e,arguments)};return t.prototype={_init:function(){var e=this;e._listeners={},e._mapCdataTags=e.makeMap("script,style"),e._arrBlocks=[],e.lastEvent=null},makeMap:function(e){for(var t={},a=e.split(","),n=0;a.length>n;n++)t[a[n]]=!0;return t},parse:function(t){function a(t,a,n,i){var r=n-b+1;i===e&&(i={}),i.raw=a,i.pos=n,i.line=w,i.col=r,L.push(i),c.fire(t,i);for(var s;s=m.exec(a);)w++,b=n+m.lastIndex}var n,i,r,s,o,l,u,d,c=this,f=c._mapCdataTags,g=/<(?:\/([^\s>]+)\s*|!--([\s\S]*?)--|!([^>]*?)|([\w\-:]+)((?:\s+[\w\-:]+(?:\s*=\s*(?:"[^"]*"|'[^']*'|[^\s"'>]*))?)*?)\s*(\/?))>/g,h=/\s*([\w\-:]+)(?:\s*=\s*(?:(")([^"]*)"|(')([^']*)'|([^\s"'>]*)))?/g,m=/\r?\n/g,p=0,v=0,b=0,w=1,L=c._arrBlocks;for(c.fire("start",{pos:0,line:1,col:1});n=g.exec(t);)if(i=n.index,i>p&&(d=t.substring(p,i),o?u.push(d):a("text",d,p)),p=g.lastIndex,!(r=n[1])||(o&&r===o&&(d=u.join(""),a("cdata",d,v,{tagName:o,attrs:l}),o=null,l=null,u=null),o))if(o)u.push(n[0]);else if(r=n[4]){s=[];for(var y,T=n[5],H=0;y=h.exec(T);){var x=y[1],M=y[2]?y[2]:y[4]?y[4]:"",N=y[3]?y[3]:y[5]?y[5]:y[6]?y[6]:"";s.push({name:x,value:N,quote:M,index:y.index,raw:y[0]}),H+=y[0].length}H===T.length?(a("tagstart",n[0],i,{tagName:r,attrs:s,close:n[6]}),f[r]&&(o=r,l=s.concat(),u=[],v=p)):a("text",n[0],i)}else(n[2]||n[3])&&a("comment",n[0],i,{content:n[2]||n[3],"long":n[2]?!0:!1});else a("tagend",n[0],i,{tagName:r});t.length>p&&(d=t.substring(p,t.length),a("text",d,p)),c.fire("end",{pos:p,line:w,col:t.length-b+1})},addListener:function(t,a){for(var n,i=this._listeners,r=t.split(/[,\s]/),s=0,o=r.length;o>s;s++)n=r[s],i[n]===e&&(i[n]=[]),i[n].push(a)},fire:function(t,a){a===e&&(a={}),a.type=t;var n=this,i=[],r=n._listeners[t],s=n._listeners.all;r!==e&&(i=i.concat(r)),s!==e&&(i=i.concat(s));var o=n.lastEvent;null!==o&&(delete o.lastEvent,a.lastEvent=o),n.lastEvent=a;for(var l=0,u=i.length;u>l;l++)i[l].call(n,a)},removeListener:function(t,a){var n=this._listeners[t];if(n!==e)for(var i=0,r=n.length;r>i;i++)if(n[i]===a){n.splice(i,1);break}},fixPos:function(e,t){var a,n=e.raw.substr(0,t),i=n.split(/\r?\n/),r=i.length-1,s=e.line;return r>0?(s+=r,a=i[r].length+1):a=e.col+t,{line:s,col:a}},getMapAttrs:function(e){for(var t,a={},n=0,i=e.length;i>n;n++)t=e[n],a[t.name]=t.value;return a}},t}();"object"==typeof exports&&exports&&(exports.HTMLParser=HTMLParser),HTMLHint.addRule({id:"alt-require",description:"The alt attribute of an <img> element must be present and alt attribute of area[href] and input[type=image] must have a value.",init:function(e,t){var a=this;e.addListener("tagstart",function(n){var i,r=n.tagName.toLowerCase(),s=e.getMapAttrs(n.attrs),o=n.col+r.length+1;"img"!==r||"alt"in s?("area"===r&&"href"in s||"input"===r&&"image"===s.type)&&("alt"in s&&""!==s.alt||(i="area"===r?"area[href]":"input[type=image]",t.warn("The alt attribute of "+i+" must have a value.",n.line,o,a,n.raw))):t.warn("An alt attribute must be present on <img> elements.",n.line,o,a,n.raw)})}}),HTMLHint.addRule({id:"attr-lowercase",description:"All attribute names must be in lowercase.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i=e.attrs,r=e.col+e.tagName.length+1,s=0,o=i.length;o>s;s++){n=i[s];var l=n.name;l!==l.toLowerCase()&&t.error("The attribute name of [ "+l+" ] must be in lowercase.",e.line,r+n.index,a,n.raw)}})}}),HTMLHint.addRule({id:"attr-no-duplication",description:"Elements cannot have duplicate attributes.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i,r=e.attrs,s=e.col+e.tagName.length+1,o={},l=0,u=r.length;u>l;l++)n=r[l],i=n.name,o[i]===!0&&t.error("Duplicate of attribute name [ "+n.name+" ] was found.",e.line,s+n.index,a,n.raw),o[i]=!0})}}),HTMLHint.addRule({id:"attr-unsafe-chars",description:"Attribute values cannot contain unsafe chars.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i,r=e.attrs,s=e.col+e.tagName.length+1,o=/[\u0000-\u0008\u000b\u000c\u000e-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/,l=0,u=r.length;u>l;l++)if(n=r[l],i=n.value.match(o),null!==i){var d=escape(i[0]).replace(/%u/,"\\u").replace(/%/,"\\x");t.warn("The value of attribute [ "+n.name+" ] cannot contain an unsafe char [ "+d+" ].",e.line,s+n.index,a,n.raw)}})}}),HTMLHint.addRule({id:"attr-value-double-quotes",description:"Attribute values must be in double quotes.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i=e.attrs,r=e.col+e.tagName.length+1,s=0,o=i.length;o>s;s++)n=i[s],(""!==n.value&&'"'!==n.quote||""===n.value&&"'"===n.quote)&&t.error("The value of attribute [ "+n.name+" ] must be in double quotes.",e.line,r+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"attr-value-not-empty",description:"All attributes must have values.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i=e.attrs,r=e.col+e.tagName.length+1,s=0,o=i.length;o>s;s++)n=i[s],""===n.quote&&""===n.value&&t.warn("The attribute [ "+n.name+" ] must have a value.",e.line,r+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"csslint",description:"Scan css with csslint.",init:function(e,t,a){var n=this;e.addListener("cdata",function(e){if("style"===e.tagName.toLowerCase()){var i;if(i="object"==typeof exports&&require?require("csslint").CSSLint.verify:CSSLint.verify,void 0!==a){var r=e.line-1,s=e.col-1;try{var o=i(e.raw,a).messages;o.forEach(function(e){var a=e.line;t["warning"===e.type?"warn":"error"]("["+e.rule.id+"] "+e.message,r+a,(1===a?s:0)+e.col,n,e.evidence)})}catch(l){}}}})}}),HTMLHint.addRule({id:"doctype-first",description:"Doctype must be declared first.",init:function(e,t){var a=this,n=function(i){"start"===i.type||"text"===i.type&&/^\s*$/.test(i.raw)||(("comment"!==i.type&&i.long===!1||/^DOCTYPE\s+/i.test(i.content)===!1)&&t.error("Doctype must be declared first.",i.line,i.col,a,i.raw),e.removeListener("all",n))};e.addListener("all",n)}}),HTMLHint.addRule({id:"doctype-html5",description:'Invalid doctype. Use: "<!DOCTYPE html>"',init:function(e,t){function a(e){e.long===!1&&"doctype html"!==e.content.toLowerCase()&&t.warn('Invalid doctype. Use: "<!DOCTYPE html>"',e.line,e.col,i,e.raw)}function n(){e.removeListener("comment",a),e.removeListener("tagstart",n)}var i=this;e.addListener("all",a),e.addListener("tagstart",n)}}),HTMLHint.addRule({id:"head-script-disabled",description:"The <script> tag cannot be used in a <head> tag.",init:function(e,t){function a(a){var n=e.getMapAttrs(a.attrs),o=n.type,l=a.tagName.toLowerCase();"head"===l&&(s=!0),s!==!0||"script"!==l||o&&r.test(o)!==!0||t.warn("The <script> tag cannot be used in a <head> tag.",a.line,a.col,i,a.raw)}function n(t){"head"===t.tagName.toLowerCase()&&(e.removeListener("tagstart",a),e.removeListener("tagend",n))}var i=this,r=/^(text\/javascript|application\/javascript)$/i,s=!1;e.addListener("tagstart",a),e.addListener("tagend",n)}}),HTMLHint.addRule({id:"href-abs-or-rel",description:"An href attribute must be either absolute or relative.",init:function(e,t,a){var n=this,i="abs"===a?"absolute":"relative";e.addListener("tagstart",function(e){for(var a,r=e.attrs,s=e.col+e.tagName.length+1,o=0,l=r.length;l>o;o++)if(a=r[o],"href"===a.name){("absolute"===i&&/^\w+?:/.test(a.value)===!1||"relative"===i&&/^https?:\/\//.test(a.value)===!0)&&t.warn("The value of the href attribute [ "+a.value+" ] must be "+i+".",e.line,s+a.index,n,a.raw);break}})}}),HTMLHint.addRule({id:"id-class-ad-disabled",description:"The id and class attributes cannot use the ad keyword, it will be blocked by adblock software.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i,r=e.attrs,s=e.col+e.tagName.length+1,o=0,l=r.length;l>o;o++)n=r[o],i=n.name,/^(id|class)$/i.test(i)&&/(^|[-\_])ad([-\_]|$)/i.test(n.value)&&t.warn("The value of attribute "+i+" cannot use the ad keyword.",e.line,s+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"id-class-value",description:"The id and class attribute values must meet the specified rules.",init:function(e,t,a){var n,i=this,r={underline:{regId:/^[a-z\d]+(_[a-z\d]+)*$/,message:"The id and class attribute values must be in lowercase and split by an underscore."},dash:{regId:/^[a-z\d]+(-[a-z\d]+)*$/,message:"The id and class attribute values must be in lowercase and split by a dash."},hump:{regId:/^[a-z][a-zA-Z\d]*([A-Z][a-zA-Z\d]*)*$/,message:"The id and class attribute values must meet the camelCase style."}};if(n="string"==typeof a?r[a]:a,n&&n.regId){var s=n.regId,o=n.message;e.addListener("tagstart",function(e){for(var a,n=e.attrs,r=e.col+e.tagName.length+1,l=0,u=n.length;u>l;l++)if(a=n[l],"id"===a.name.toLowerCase()&&s.test(a.value)===!1&&t.warn(o,e.line,r+a.index,i,a.raw),"class"===a.name.toLowerCase())for(var d,c=a.value.split(/\s+/g),f=0,g=c.length;g>f;f++)d=c[f],d&&s.test(d)===!1&&t.warn(o,e.line,r+a.index,i,d)})}}}),HTMLHint.addRule({id:"id-unique",description:"The value of id attributes must be unique.",init:function(e,t){var a=this,n={};e.addListener("tagstart",function(e){for(var i,r,s=e.attrs,o=e.col+e.tagName.length+1,l=0,u=s.length;u>l;l++)if(i=s[l],"id"===i.name.toLowerCase()){r=i.value,r&&(void 0===n[r]?n[r]=1:n[r]++,n[r]>1&&t.error("The id value [ "+r+" ] must be unique.",e.line,o+i.index,a,i.raw));break}})}}),HTMLHint.addRule({id:"inline-script-disabled",description:"Inline script cannot be use.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i,r=e.attrs,s=e.col+e.tagName.length+1,o=/^on(unload|message|submit|select|scroll|resize|mouseover|mouseout|mousemove|mouseleave|mouseenter|mousedown|load|keyup|keypress|keydown|focus|dblclick|click|change|blur|error)$/i,l=0,u=r.length;u>l;l++)n=r[l],i=n.name.toLowerCase(),o.test(i)===!0?t.warn("Inline script [ "+n.raw+" ] cannot be use.",e.line,s+n.index,a,n.raw):("src"===i||"href"===i)&&/^\s*javascript:/i.test(n.value)&&t.warn("Inline script [ "+n.raw+" ] cannot be use.",e.line,s+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"inline-style-disabled",description:"Inline style cannot be use.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i=e.attrs,r=e.col+e.tagName.length+1,s=0,o=i.length;o>s;s++)n=i[s],"style"===n.name.toLowerCase()&&t.warn("Inline style [ "+n.raw+" ] cannot be use.",e.line,r+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"jshint",description:"Scan script with jshint.",init:function(e,t,a){var n=this;e.addListener("cdata",function(i){if("script"===i.tagName.toLowerCase()){var r=e.getMapAttrs(i.attrs),s=r.type;if(void 0!==r.src||s&&/^(text\/javascript)$/i.test(s)===!1)return;var o;if(o="object"==typeof exports&&require?require("jshint").JSHINT:JSHINT,void 0!==a){var l=i.line-1,u=i.col-1,d=i.raw.replace(/\t/g," ");try{var c=o(d,a);c===!1&&o.errors.forEach(function(e){var a=e.line;t.warn(e.reason,l+a,(1===a?u:0)+e.character,n,e.evidence)})}catch(f){}}}})}}),HTMLHint.addRule({id:"space-tab-mixed-disabled",description:"Do not mix tabs and spaces for indentation.",init:function(e,t,a){var n=this;e.addListener("text",function(i){for(var r,s=i.raw,o=/(^|\r?\n)([ \t]+)/g;r=o.exec(s);){var l=e.fixPos(i,r.index+r[1].length);"space"===a&&/^ +$/.test(r[2])===!1?t.warn("Please use space for indentation.",l.line,1,n,i.raw):"tab"===a&&/^\t+$/.test(r[2])===!1?t.warn("Please use tab for indentation.",l.line,1,n,i.raw):/ +\t|\t+ /.test(r[2])===!0&&t.warn("Do not mix tabs and spaces for indentation.",l.line,1,n,i.raw)}})}}),HTMLHint.addRule({id:"spec-char-escape",description:"Special characters must be escaped.",init:function(e,t){var a=this;e.addListener("text",function(n){for(var i,r=n.raw,s=/[<>]/g;i=s.exec(r);){var o=e.fixPos(n,i.index);t.error("Special characters must be escaped : [ "+i[0]+" ].",o.line,o.col,a,n.raw)}})}}),HTMLHint.addRule({id:"src-not-empty",description:"The src attribute of an img(script,link) must have a value.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){for(var n,i=e.tagName,r=e.attrs,s=e.col+i.length+1,o=0,l=r.length;l>o;o++)n=r[o],(/^(img|script|embed|bgsound|iframe)$/.test(i)===!0&&"src"===n.name||"link"===i&&"href"===n.name||"object"===i&&"data"===n.name)&&""===n.value&&t.error("The attribute [ "+n.name+" ] of the tag [ "+i+" ] must have a value.",e.line,s+n.index,a,n.raw)})}}),HTMLHint.addRule({id:"style-disabled",description:"<style> tags cannot be used.",init:function(e,t){var a=this;e.addListener("tagstart",function(e){"style"===e.tagName.toLowerCase()&&t.warn("The <style> tag cannot be used.",e.line,e.col,a,e.raw)})}}),HTMLHint.addRule({id:"tag-pair",description:"Tag must be paired.",init:function(e,t){var a=this,n=[],i=e.makeMap("area,base,basefont,br,col,embed,frame,hr,img,input,isindex,keygen,link,meta,param,source,track");e.addListener("tagstart",function(e){var t=e.tagName.toLowerCase();void 0!==i[t]||e.close||n.push({tagName:t,line:e.line,raw:e.raw})}),e.addListener("tagend",function(e){for(var i=e.tagName.toLowerCase(),r=n.length-1;r>=0&&n[r].tagName!==i;r--);if(r>=0){for(var s=[],o=n.length-1;o>r;o--)s.push("</"+n[o].tagName+">");if(s.length>0){var l=n[n.length-1];t.error("Tag must be paired, missing: [ "+s.join("")+" ], start tag match failed [ "+l.raw+" ] on line "+l.line+".",e.line,e.col,a,e.raw)}n.length=r}else t.error("Tag must be paired, no start tag: [ "+e.raw+" ]",e.line,e.col,a,e.raw)}),e.addListener("end",function(e){for(var i=[],r=n.length-1;r>=0;r--)i.push("</"+n[r].tagName+">");if(i.length>0){var s=n[n.length-1];t.error("Tag must be paired, missing: [ "+i.join("")+" ], open tag match failed [ "+s.raw+" ] on line "+s.line+".",e.line,e.col,a,"")}})}}),HTMLHint.addRule({id:"tag-self-close",description:"Empty tags must be self closed.",init:function(e,t){var a=this,n=e.makeMap("area,base,basefont,br,col,frame,hr,img,input,isindex,link,meta,param,embed");e.addListener("tagstart",function(e){var i=e.tagName.toLowerCase();void 0!==n[i]&&(e.close||t.warn("The empty tag : [ "+i+" ] must be self closed.",e.line,e.col,a,e.raw))})}}),HTMLHint.addRule({id:"tagname-lowercase",description:"All html element names must be in lowercase.",init:function(e,t){var a=this;e.addListener("tagstart,tagend",function(e){var n=e.tagName;n!==n.toLowerCase()&&t.error("The html element name of [ "+n+" ] must be in lowercase.",e.line,e.col,a,e.raw)})}}),HTMLHint.addRule({id:"title-require",description:"<title> must be present in <head> tag.",init:function(e,t){function a(e){var t=e.tagName.toLowerCase();"head"===t?r=!0:"title"===t&&r&&(s=!0)}function n(r){var o=r.tagName.toLowerCase();if(s&&"title"===o){var l=r.lastEvent;("text"!==l.type||"text"===l.type&&/^\s*$/.test(l.raw)===!0)&&t.error("<title></title> must not be empty.",r.line,r.col,i,r.raw)}else"head"===o&&(s===!1&&t.error("<title> must be present in <head> tag.",r.line,r.col,i,r.raw),e.removeListener("tagstart",a),e.removeListener("tagend",n))}var i=this,r=!1,s=!1;e.addListener("tagstart",a),e.addListener("tagend",n)}});
},{"csslint":191,"jshint":191}],187:[function(require,module,exports){
// Generated by CoffeeScript 1.9.2

/*
jQuery Growl
Copyright 2015 Kevin Sylvestre
1.2.6
 */

(function() {
  "use strict";
  var $, Animation, Growl,
    bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  $ = jQuery;

  Animation = (function() {
    function Animation() {}

    Animation.transitions = {
      "webkitTransition": "webkitTransitionEnd",
      "mozTransition": "mozTransitionEnd",
      "oTransition": "oTransitionEnd",
      "transition": "transitionend"
    };

    Animation.transition = function($el) {
      var el, ref, result, type;
      el = $el[0];
      ref = this.transitions;
      for (type in ref) {
        result = ref[type];
        if (el.style[type] != null) {
          return result;
        }
      }
    };

    return Animation;

  })();

  Growl = (function() {
    Growl.settings = {
      namespace: 'growl',
      duration: 3200,
      close: "&#215;",
      location: "default",
      style: "default",
      size: "medium"
    };

    Growl.growl = function(settings) {
      if (settings == null) {
        settings = {};
      }
      this.initialize();
      return new Growl(settings);
    };

    Growl.initialize = function() {
      return $("body:not(:has(#growls))").append('<div id="growls" />');
    };

    function Growl(settings) {
      if (settings == null) {
        settings = {};
      }
      this.html = bind(this.html, this);
      this.$growl = bind(this.$growl, this);
      this.$growls = bind(this.$growls, this);
      this.animate = bind(this.animate, this);
      this.remove = bind(this.remove, this);
      this.dismiss = bind(this.dismiss, this);
      this.present = bind(this.present, this);
      this.cycle = bind(this.cycle, this);
      this.close = bind(this.close, this);
      this.unbind = bind(this.unbind, this);
      this.bind = bind(this.bind, this);
      this.render = bind(this.render, this);
      this.settings = $.extend({}, Growl.settings, settings);
      this.$growls().attr('class', this.settings.location);
      this.render();
    }

    Growl.prototype.render = function() {
      var $growl;
      $growl = this.$growl();
      this.$growls().append($growl);
      if (this.settings['static'] != null) {
        if (typeof console !== "undefined" && console !== null) {
          if (typeof console.debug === "function") {
            console.debug('DEPRECATION: static has been renamed to fix and will be removed in the next release');
          }
        }
        this.settings['fixed'] = this.settings['static'];
      }
      if (this.settings.fixed) {
        this.present();
      } else {
        this.cycle();
      }
    };

    Growl.prototype.bind = function($growl) {
      if ($growl == null) {
        $growl = this.$growl();
      }
      return $growl.on("contextmenu", this.close).find("." + this.settings.namespace + "-close").on("click", this.close);
    };

    Growl.prototype.unbind = function($growl) {
      if ($growl == null) {
        $growl = this.$growl();
      }
      return $growl.off("contextmenu", this.close).find("." + this.settings.namespace + "-close").off("click", this.close);
    };

    Growl.prototype.close = function(event) {
      var $growl;
      event.preventDefault();
      event.stopPropagation();
      $growl = this.$growl();
      return $growl.stop().queue(this.dismiss).queue(this.remove);
    };

    Growl.prototype.cycle = function() {
      var $growl;
      $growl = this.$growl();
      return $growl.queue(this.present).delay(this.settings.duration).queue(this.dismiss).queue(this.remove);
    };

    Growl.prototype.present = function(callback) {
      var $growl;
      $growl = this.$growl();
      this.bind($growl);
      return this.animate($growl, this.settings.namespace + "-incoming", 'out', callback);
    };

    Growl.prototype.dismiss = function(callback) {
      var $growl;
      $growl = this.$growl();
      this.unbind($growl);
      return this.animate($growl, this.settings.namespace + "-outgoing", 'in', callback);
    };

    Growl.prototype.remove = function(callback) {
      this.$growl().remove();
      return callback();
    };

    Growl.prototype.animate = function($element, name, direction, callback) {
      var transition;
      if (direction == null) {
        direction = 'in';
      }
      transition = Animation.transition($element);
      $element[direction === 'in' ? 'removeClass' : 'addClass'](name);
      $element.offset().position;
      $element[direction === 'in' ? 'addClass' : 'removeClass'](name);
      if (callback == null) {
        return;
      }
      if (transition != null) {
        $element.one(transition, callback);
      } else {
        callback();
      }
    };

    Growl.prototype.$growls = function() {
      return this.$_growls != null ? this.$_growls : this.$_growls = $('#growls');
    };

    Growl.prototype.$growl = function() {
      return this.$_growl != null ? this.$_growl : this.$_growl = $(this.html());
    };

    Growl.prototype.html = function() {
      return "<div class='" + this.settings.namespace + " " + this.settings.namespace + "-" + this.settings.style + " " + this.settings.namespace + "-" + this.settings.size + "'>\n  <div class='" + this.settings.namespace + "-close'>" + this.settings.close + "</div>\n  <div class='" + this.settings.namespace + "-title'>" + this.settings.title + "</div>\n  <div class='" + this.settings.namespace + "-message'>" + this.settings.message + "</div>\n</div>";
    };

    return Growl;

  })();

  $.growl = function(options) {
    if (options == null) {
      options = {};
    }
    return Growl.growl(options);
  };

  $.growl.error = function(options) {
    var settings;
    if (options == null) {
      options = {};
    }
    settings = {
      title: "Error!",
      style: "error"
    };
    return $.growl($.extend(settings, options));
  };

  $.growl.notice = function(options) {
    var settings;
    if (options == null) {
      options = {};
    }
    settings = {
      title: "Notice!",
      style: "notice"
    };
    return $.growl($.extend(settings, options));
  };

  $.growl.warning = function(options) {
    var settings;
    if (options == null) {
      options = {};
    }
    settings = {
      title: "Warning!",
      style: "warning"
    };
    return $.growl($.extend(settings, options));
  };

}).call(this);
},{}],188:[function(require,module,exports){
//https://github.com/customd/jquery-visible
(function($){

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *       the user visible viewport of a web browser.
     *       only accounts for vertical position, not horizontal.
     */
    var $w = $(window);
    $.fn.visible = function(partial,hidden,direction){

        if (this.length < 1)
            return;

        var $t        = this.length > 1 ? this.eq(0) : this,
            t         = $t.get(0),
            vpWidth   = $w.width(),
            vpHeight  = $w.height(),
            direction = (direction) ? direction : 'both',
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function'){

            // Use this native browser method, if available.
            var rec = t.getBoundingClientRect(),
                tViz = rec.top    >= 0 && rec.top    <  vpHeight,
                bViz = rec.bottom >  0 && rec.bottom <= vpHeight,
                lViz = rec.left   >= 0 && rec.left   <  vpWidth,
                rViz = rec.right  >  0 && rec.right  <= vpWidth,
                vVisible   = partial ? tViz || bViz : tViz && bViz,
                hVisible   = partial ? lViz || rViz : lViz && rViz;

            if(direction === 'both')
                return clientSize && vVisible && hVisible;
            else if(direction === 'vertical')
                return clientSize && vVisible;
            else if(direction === 'horizontal')
                return clientSize && hVisible;
        } else {

            var viewTop         = $w.scrollTop(),
                viewBottom      = viewTop + vpHeight,
                viewLeft        = $w.scrollLeft(),
                viewRight       = viewLeft + vpWidth,
                offset          = $t.offset(),
                _top            = offset.top,
                _bottom         = _top + $t.height(),
                _left           = offset.left,
                _right          = _left + $t.width(),
                compareTop      = partial === true ? _bottom : _top,
                compareBottom   = partial === true ? _top : _bottom,
                compareLeft     = partial === true ? _right : _left,
                compareRight    = partial === true ? _left : _right;

            if(direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if(direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if(direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };

})(jQuery);
},{}],189:[function(require,module,exports){
//https://github.com/kmewhort/pointer_events_polyfill
/*
 * Pointer Events Polyfill: Adds support for the style attribute "pointer-events: none" to browsers without this feature (namely, IE).
 * (c) 2013, Kent Mewhort, licensed under BSD. See LICENSE.txt for details.
 */

// constructor
function PointerEventsPolyfill(options){
    // set defaults
    this.options = {
        selector: '*',
        mouseEvents: [ 'click','dblclick','mousedown','mouseup', 'mouseenter', 'mouseleave', 'mouseover' ],
        usePolyfillIf: function(){
            if(navigator.appName == 'Microsoft Internet Explorer')
            {
                var agent = navigator.userAgent;
                if (agent.match(/MSIE ([0-9]{1,}[\.0-9]{0,})/) != null){
                    var version = parseFloat( RegExp.$1 );
                    if(version < 11)
                      return true;
                }
            }
            return false;
        }
    };
    if(options){
        var obj = this;
        $.each(options, function(k,v){
          obj.options[k] = v;
        });
    }

    if(this.options.usePolyfillIf())
      this.register_mouse_events();
}

// singleton initializer
PointerEventsPolyfill.initialize = function(options){
    if(PointerEventsPolyfill.singleton == null)
      PointerEventsPolyfill.singleton = new PointerEventsPolyfill(options);
    return PointerEventsPolyfill.singleton;
};

// handle mouse events w/ support for pointer-events: none
PointerEventsPolyfill.prototype.register_mouse_events = function(){
    // register on all elements (and all future elements) matching the selector
    $(document).on(this.options.mouseEvents.join(" "), this.options.selector, function(e){
       if($(this).css('pointer-events') == 'none'){
             // peak at the element below
             var origDisplayAttribute = $(this).css('display');
             $(this).css('display','none');

             var underneathElem = document.elementFromPoint(e.clientX, e.clientY);

            if(origDisplayAttribute)
                $(this)
                    .css('display', origDisplayAttribute);
            else
                $(this).css('display','');

             // fire the mouse event on the element below
            e.target = underneathElem;
            $(underneathElem).trigger(e);

            return false;
        }
        return true;
    });
};
},{}],190:[function(require,module,exports){
// Modified to fix transparent pixels being calculated as black (https://github.com/briangonzalez/rgbaster.js/issues/8)
;(function(window, undefined){

  "use strict";

  // Helper functions.
  var getContext = function(){
    return document.createElement("canvas").getContext('2d');
  };

  var getImageData = function(img, loaded){

    var imgObj = new Image();
    var imgSrc = img.src || img;

    // Can't set cross origin to be anonymous for data url's
    // https://github.com/mrdoob/three.js/issues/1305
    if ( imgSrc.substring(0,5) !== 'data:' )
      imgObj.crossOrigin = "Anonymous";

    imgObj.onload = function(){
      var context = getContext('2d');
      context.drawImage(imgObj, 0, 0);

      var imageData = context.getImageData(0, 0, imgObj.width, imgObj.height);
      loaded && loaded(imageData.data);
    };

    imgObj.src = imgSrc;

  };

  var makeRGB = function(name){
    return ['rgb(', name, ')'].join('');
  };

  var mapPalette = function(palette){
    return palette.map(function(c){ return makeRGB(c.name); });
  };


  // RGBaster Object
  // ---------------
  //
  var BLOCKSIZE = 5;
  var PALETTESIZE = 10;

  var RGBaster = {};

  RGBaster.colors = function(img, opts){

    opts = opts || {};
    var exclude = opts.exclude || [ ], // for example, to exlude white and black:  [ '0,0,0', '255,255,255' ]
        paletteSize = opts.paletteSize || PALETTESIZE;

    getImageData(img, function(data){

              var length        = ( img.width * img.height ) || data.length,
                  colorCounts   = {},
                  rgbString     = '',
                  rgb           = [],
                  colors        = {
                    dominant: { name: '', count: 0 },
                    palette:  Array.apply(null, new Array(paletteSize)).map(Boolean).map(function(a){ return { name: '0,0,0', count: 0 }; })
                  };

              // Loop over all pixels, in BLOCKSIZE iterations.
              var i = 0;
              while ( i < length ) {
                rgb[0] = data[i];
                rgb[1] = data[i+1];
                rgb[2] = data[i+2];
                rgbString = rgb.join(",");

                // Increment!
                i += BLOCKSIZE * 4;

                // Ignore transparent pixels
                if (data[i+3] === 0) {
                  continue;
                }

                // Keep track of counts.
                if ( rgbString in colorCounts ) {
                  colorCounts[rgbString] = colorCounts[rgbString] + 1;
                }
                else{
                  colorCounts[rgbString] = 1;
                }

                // Find dominant and palette, ignoring those colors in the exclude list.
                if ( exclude.indexOf( makeRGB(rgbString) ) === -1 ) {
                  var colorCount = colorCounts[rgbString];
                  if ( colorCount > colors.dominant.count ){
                    colors.dominant.name = rgbString;
                    colors.dominant.count = colorCount;
                  } else {
                    colors.palette.some(function(c){
                      if ( colorCount > c.count ) {
                        c.name = rgbString;
                        c.count = colorCount;
                        return true;
                      }
                    });
                  }
                }

              }

              if ( opts.success ) {
                var palette = mapPalette(colors.palette);
                opts.success({
                  dominant: makeRGB(colors.dominant.name),
                  secondary: palette[0],
                  palette:  palette
                });
              }
    });
  };

  window.RGBaster = window.RGBaster || RGBaster;

})(window);
},{}],191:[function(require,module,exports){

},{}],192:[function(require,module,exports){
'use strict';

var ticky = require('ticky');

module.exports = function debounce (fn, args, ctx) {
  if (!fn) { return; }
  ticky(function run () {
    fn.apply(ctx || null, args || []);
  });
};

},{"ticky":195}],193:[function(require,module,exports){
'use strict';

var atoa = require('atoa');
var debounce = require('./debounce');

module.exports = function emitter (thing, options) {
  var opts = options || {};
  var evt = {};
  if (thing === undefined) { thing = {}; }
  thing.on = function (type, fn) {
    if (!evt[type]) {
      evt[type] = [fn];
    } else {
      evt[type].push(fn);
    }
    return thing;
  };
  thing.once = function (type, fn) {
    fn._once = true; // thing.off(fn) still works!
    thing.on(type, fn);
    return thing;
  };
  thing.off = function (type, fn) {
    var c = arguments.length;
    if (c === 1) {
      delete evt[type];
    } else if (c === 0) {
      evt = {};
    } else {
      var et = evt[type];
      if (!et) { return thing; }
      et.splice(et.indexOf(fn), 1);
    }
    return thing;
  };
  thing.emit = function () {
    var args = atoa(arguments);
    return thing.emitterSnapshot(args.shift()).apply(this, args);
  };
  thing.emitterSnapshot = function (type) {
    var et = (evt[type] || []).slice(0);
    return function () {
      var args = atoa(arguments);
      var ctx = this || thing;
      if (type === 'error' && opts.throws !== false && !et.length) { throw args.length === 1 ? args[0] : args; }
      et.forEach(function emitter (listen) {
        if (opts.async) { debounce(listen, args, ctx); } else { listen.apply(ctx, args); }
        if (listen._once) { thing.off(type, listen); }
      });
      return thing;
    };
  };
  return thing;
};

},{"./debounce":192,"atoa":194}],194:[function(require,module,exports){
module.exports = function atoa (a, n) { return Array.prototype.slice.call(a, n); }

},{}],195:[function(require,module,exports){
var si = typeof setImmediate === 'function', tick;
if (si) {
  tick = function (fn) { setImmediate(fn); };
} else {
  tick = function (fn) { setTimeout(fn, 0); };
}

module.exports = tick;
},{}],196:[function(require,module,exports){
(function (global){

var NativeCustomEvent = global.CustomEvent;

function useNative () {
  try {
    var p = new NativeCustomEvent('cat', { detail: { foo: 'bar' } });
    return  'cat' === p.type && 'bar' === p.detail.foo;
  } catch (e) {
  }
  return false;
}

/**
 * Cross-browser `CustomEvent` constructor.
 *
 * https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent.CustomEvent
 *
 * @public
 */

module.exports = useNative() ? NativeCustomEvent :

// IE >= 9
'function' === typeof document.createEvent ? function CustomEvent (type, params) {
  var e = document.createEvent('CustomEvent');
  if (params) {
    e.initCustomEvent(type, params.bubbles, params.cancelable, params.detail);
  } else {
    e.initCustomEvent(type, false, false, void 0);
  }
  return e;
} :

// IE <= 8
function CustomEvent (type, params) {
  var e = document.createEventObject();
  e.type = type;
  if (params) {
    e.bubbles = Boolean(params.bubbles);
    e.cancelable = Boolean(params.cancelable);
    e.detail = params.detail;
  } else {
    e.bubbles = false;
    e.cancelable = false;
    e.detail = void 0;
  }
  return e;
}

}).call(this,window || {})

},{}],197:[function(require,module,exports){
(function (global){
'use strict';

var customEvent = require('custom-event');
var eventmap = require('./eventmap');
var doc = global.document;
var addEvent = addEventEasy;
var removeEvent = removeEventEasy;
var hardCache = [];

if (!global.addEventListener) {
  addEvent = addEventHard;
  removeEvent = removeEventHard;
}

module.exports = {
  add: addEvent,
  remove: removeEvent,
  fabricate: fabricateEvent
};

function addEventEasy (el, type, fn, capturing) {
  return el.addEventListener(type, fn, capturing);
}

function addEventHard (el, type, fn) {
  return el.attachEvent('on' + type, wrap(el, type, fn));
}

function removeEventEasy (el, type, fn, capturing) {
  return el.removeEventListener(type, fn, capturing);
}

function removeEventHard (el, type, fn) {
  var listener = unwrap(el, type, fn);
  if (listener) {
    return el.detachEvent('on' + type, listener);
  }
}

function fabricateEvent (el, type, model) {
  var e = eventmap.indexOf(type) === -1 ? makeCustomEvent() : makeClassicEvent();
  if (el.dispatchEvent) {
    el.dispatchEvent(e);
  } else {
    el.fireEvent('on' + type, e);
  }
  function makeClassicEvent () {
    var e;
    if (doc.createEvent) {
      e = doc.createEvent('Event');
      e.initEvent(type, true, true);
    } else if (doc.createEventObject) {
      e = doc.createEventObject();
    }
    return e;
  }
  function makeCustomEvent () {
    return new customEvent(type, { detail: model });
  }
}

function wrapperFactory (el, type, fn) {
  return function wrapper (originalEvent) {
    var e = originalEvent || global.event;
    e.target = e.target || e.srcElement;
    e.preventDefault = e.preventDefault || function preventDefault () { e.returnValue = false; };
    e.stopPropagation = e.stopPropagation || function stopPropagation () { e.cancelBubble = true; };
    e.which = e.which || e.keyCode;
    fn.call(el, e);
  };
}

function wrap (el, type, fn) {
  var wrapper = unwrap(el, type, fn) || wrapperFactory(el, type, fn);
  hardCache.push({
    wrapper: wrapper,
    element: el,
    type: type,
    fn: fn
  });
  return wrapper;
}

function unwrap (el, type, fn) {
  var i = find(el, type, fn);
  if (i) {
    var wrapper = hardCache[i].wrapper;
    hardCache.splice(i, 1); // free up a tad of memory
    return wrapper;
  }
}

function find (el, type, fn) {
  var i, item;
  for (i = 0; i < hardCache.length; i++) {
    item = hardCache[i];
    if (item.element === el && item.type === type && item.fn === fn) {
      return i;
    }
  }
}

}).call(this,window || {})

},{"./eventmap":198,"custom-event":196}],198:[function(require,module,exports){
(function (global){
'use strict';

var eventmap = [];
var eventname = '';
var ron = /^on/;

for (eventname in global) {
  if (ron.test(eventname)) {
    eventmap.push(eventname.slice(2));
  }
}

module.exports = eventmap;

}).call(this,window || {})

},{}],199:[function(require,module,exports){
/*global define:false */
/**
 * Copyright 2015 Craig Campbell
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Mousetrap is a simple keyboard shortcut library for Javascript with
 * no external dependencies
 *
 * @version 1.5.3
 * @url craig.is/killing/mice
 */
(function(window, document, undefined) {

    /**
     * mapping of special keycodes to their corresponding keys
     *
     * everything in this dictionary cannot use keypress events
     * so it has to be here to map to the correct keycodes for
     * keyup/keydown events
     *
     * @type {Object}
     */
    var _MAP = {
        8: 'backspace',
        9: 'tab',
        13: 'enter',
        16: 'shift',
        17: 'ctrl',
        18: 'alt',
        20: 'capslock',
        27: 'esc',
        32: 'space',
        33: 'pageup',
        34: 'pagedown',
        35: 'end',
        36: 'home',
        37: 'left',
        38: 'up',
        39: 'right',
        40: 'down',
        45: 'ins',
        46: 'del',
        91: 'meta',
        93: 'meta',
        224: 'meta'
    };

    /**
     * mapping for special characters so they can support
     *
     * this dictionary is only used incase you want to bind a
     * keyup or keydown event to one of these keys
     *
     * @type {Object}
     */
    var _KEYCODE_MAP = {
        106: '*',
        107: '+',
        109: '-',
        110: '.',
        111 : '/',
        186: ';',
        187: '=',
        188: ',',
        189: '-',
        190: '.',
        191: '/',
        192: '`',
        219: '[',
        220: '\\',
        221: ']',
        222: '\''
    };

    /**
     * this is a mapping of keys that require shift on a US keypad
     * back to the non shift equivelents
     *
     * this is so you can use keyup events with these keys
     *
     * note that this will only work reliably on US keyboards
     *
     * @type {Object}
     */
    var _SHIFT_MAP = {
        '~': '`',
        '!': '1',
        '@': '2',
        '#': '3',
        '$': '4',
        '%': '5',
        '^': '6',
        '&': '7',
        '*': '8',
        '(': '9',
        ')': '0',
        '_': '-',
        '+': '=',
        ':': ';',
        '\"': '\'',
        '<': ',',
        '>': '.',
        '?': '/',
        '|': '\\'
    };

    /**
     * this is a list of special strings you can use to map
     * to modifier keys when you specify your keyboard shortcuts
     *
     * @type {Object}
     */
    var _SPECIAL_ALIASES = {
        'option': 'alt',
        'command': 'meta',
        'return': 'enter',
        'escape': 'esc',
        'plus': '+',
        'mod': /Mac|iPod|iPhone|iPad/.test(navigator.platform) ? 'meta' : 'ctrl'
    };

    /**
     * variable to store the flipped version of _MAP from above
     * needed to check if we should use keypress or not when no action
     * is specified
     *
     * @type {Object|undefined}
     */
    var _REVERSE_MAP;

    /**
     * loop through the f keys, f1 to f19 and add them to the map
     * programatically
     */
    for (var i = 1; i < 20; ++i) {
        _MAP[111 + i] = 'f' + i;
    }

    /**
     * loop through to map numbers on the numeric keypad
     */
    for (i = 0; i <= 9; ++i) {
        _MAP[i + 96] = i;
    }

    /**
     * cross browser add event method
     *
     * @param {Element|HTMLDocument} object
     * @param {string} type
     * @param {Function} callback
     * @returns void
     */
    function _addEvent(object, type, callback) {
        if (object.addEventListener) {
            object.addEventListener(type, callback, false);
            return;
        }

        object.attachEvent('on' + type, callback);
    }

    /**
     * takes the event and returns the key character
     *
     * @param {Event} e
     * @return {string}
     */
    function _characterFromEvent(e) {

        // for keypress events we should return the character as is
        if (e.type == 'keypress') {
            var character = String.fromCharCode(e.which);

            // if the shift key is not pressed then it is safe to assume
            // that we want the character to be lowercase.  this means if
            // you accidentally have caps lock on then your key bindings
            // will continue to work
            //
            // the only side effect that might not be desired is if you
            // bind something like 'A' cause you want to trigger an
            // event when capital A is pressed caps lock will no longer
            // trigger the event.  shift+a will though.
            if (!e.shiftKey) {
                character = character.toLowerCase();
            }

            return character;
        }

        // for non keypress events the special maps are needed
        if (_MAP[e.which]) {
            return _MAP[e.which];
        }

        if (_KEYCODE_MAP[e.which]) {
            return _KEYCODE_MAP[e.which];
        }

        // if it is not in the special map

        // with keydown and keyup events the character seems to always
        // come in as an uppercase character whether you are pressing shift
        // or not.  we should make sure it is always lowercase for comparisons
        return String.fromCharCode(e.which).toLowerCase();
    }

    /**
     * checks if two arrays are equal
     *
     * @param {Array} modifiers1
     * @param {Array} modifiers2
     * @returns {boolean}
     */
    function _modifiersMatch(modifiers1, modifiers2) {
        return modifiers1.sort().join(',') === modifiers2.sort().join(',');
    }

    /**
     * takes a key event and figures out what the modifiers are
     *
     * @param {Event} e
     * @returns {Array}
     */
    function _eventModifiers(e) {
        var modifiers = [];

        if (e.shiftKey) {
            modifiers.push('shift');
        }

        if (e.altKey) {
            modifiers.push('alt');
        }

        if (e.ctrlKey) {
            modifiers.push('ctrl');
        }

        if (e.metaKey) {
            modifiers.push('meta');
        }

        return modifiers;
    }

    /**
     * prevents default for this event
     *
     * @param {Event} e
     * @returns void
     */
    function _preventDefault(e) {
        if (e.preventDefault) {
            e.preventDefault();
            return;
        }

        e.returnValue = false;
    }

    /**
     * stops propogation for this event
     *
     * @param {Event} e
     * @returns void
     */
    function _stopPropagation(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
            return;
        }

        e.cancelBubble = true;
    }

    /**
     * determines if the keycode specified is a modifier key or not
     *
     * @param {string} key
     * @returns {boolean}
     */
    function _isModifier(key) {
        return key == 'shift' || key == 'ctrl' || key == 'alt' || key == 'meta';
    }

    /**
     * reverses the map lookup so that we can look for specific keys
     * to see what can and can't use keypress
     *
     * @return {Object}
     */
    function _getReverseMap() {
        if (!_REVERSE_MAP) {
            _REVERSE_MAP = {};
            for (var key in _MAP) {

                // pull out the numeric keypad from here cause keypress should
                // be able to detect the keys from the character
                if (key > 95 && key < 112) {
                    continue;
                }

                if (_MAP.hasOwnProperty(key)) {
                    _REVERSE_MAP[_MAP[key]] = key;
                }
            }
        }
        return _REVERSE_MAP;
    }

    /**
     * picks the best action based on the key combination
     *
     * @param {string} key - character for key
     * @param {Array} modifiers
     * @param {string=} action passed in
     */
    function _pickBestAction(key, modifiers, action) {

        // if no action was picked in we should try to pick the one
        // that we think would work best for this key
        if (!action) {
            action = _getReverseMap()[key] ? 'keydown' : 'keypress';
        }

        // modifier keys don't work as expected with keypress,
        // switch to keydown
        if (action == 'keypress' && modifiers.length) {
            action = 'keydown';
        }

        return action;
    }

    /**
     * Converts from a string key combination to an array
     *
     * @param  {string} combination like "command+shift+l"
     * @return {Array}
     */
    function _keysFromString(combination) {
        if (combination === '+') {
            return ['+'];
        }

        combination = combination.replace(/\+{2}/g, '+plus');
        return combination.split('+');
    }

    /**
     * Gets info for a specific key combination
     *
     * @param  {string} combination key combination ("command+s" or "a" or "*")
     * @param  {string=} action
     * @returns {Object}
     */
    function _getKeyInfo(combination, action) {
        var keys;
        var key;
        var i;
        var modifiers = [];

        // take the keys from this pattern and figure out what the actual
        // pattern is all about
        keys = _keysFromString(combination);

        for (i = 0; i < keys.length; ++i) {
            key = keys[i];

            // normalize key names
            if (_SPECIAL_ALIASES[key]) {
                key = _SPECIAL_ALIASES[key];
            }

            // if this is not a keypress event then we should
            // be smart about using shift keys
            // this will only work for US keyboards however
            if (action && action != 'keypress' && _SHIFT_MAP[key]) {
                key = _SHIFT_MAP[key];
                modifiers.push('shift');
            }

            // if this key is a modifier then add it to the list of modifiers
            if (_isModifier(key)) {
                modifiers.push(key);
            }
        }

        // depending on what the key combination is
        // we will try to pick the best event for it
        action = _pickBestAction(key, modifiers, action);

        return {
            key: key,
            modifiers: modifiers,
            action: action
        };
    }

    function _belongsTo(element, ancestor) {
        if (element === null || element === document) {
            return false;
        }

        if (element === ancestor) {
            return true;
        }

        return _belongsTo(element.parentNode, ancestor);
    }

    function Mousetrap(targetElement) {
        var self = this;

        targetElement = targetElement || document;

        if (!(self instanceof Mousetrap)) {
            return new Mousetrap(targetElement);
        }

        /**
         * element to attach key events to
         *
         * @type {Element}
         */
        self.target = targetElement;

        /**
         * a list of all the callbacks setup via Mousetrap.bind()
         *
         * @type {Object}
         */
        self._callbacks = {};

        /**
         * direct map of string combinations to callbacks used for trigger()
         *
         * @type {Object}
         */
        self._directMap = {};

        /**
         * keeps track of what level each sequence is at since multiple
         * sequences can start out with the same sequence
         *
         * @type {Object}
         */
        var _sequenceLevels = {};

        /**
         * variable to store the setTimeout call
         *
         * @type {null|number}
         */
        var _resetTimer;

        /**
         * temporary state where we will ignore the next keyup
         *
         * @type {boolean|string}
         */
        var _ignoreNextKeyup = false;

        /**
         * temporary state where we will ignore the next keypress
         *
         * @type {boolean}
         */
        var _ignoreNextKeypress = false;

        /**
         * are we currently inside of a sequence?
         * type of action ("keyup" or "keydown" or "keypress") or false
         *
         * @type {boolean|string}
         */
        var _nextExpectedAction = false;

        /**
         * resets all sequence counters except for the ones passed in
         *
         * @param {Object} doNotReset
         * @returns void
         */
        function _resetSequences(doNotReset) {
            doNotReset = doNotReset || {};

            var activeSequences = false,
                key;

            for (key in _sequenceLevels) {
                if (doNotReset[key]) {
                    activeSequences = true;
                    continue;
                }
                _sequenceLevels[key] = 0;
            }

            if (!activeSequences) {
                _nextExpectedAction = false;
            }
        }

        /**
         * finds all callbacks that match based on the keycode, modifiers,
         * and action
         *
         * @param {string} character
         * @param {Array} modifiers
         * @param {Event|Object} e
         * @param {string=} sequenceName - name of the sequence we are looking for
         * @param {string=} combination
         * @param {number=} level
         * @returns {Array}
         */
        function _getMatches(character, modifiers, e, sequenceName, combination, level) {
            var i;
            var callback;
            var matches = [];
            var action = e.type;

            // if there are no events related to this keycode
            if (!self._callbacks[character]) {
                return [];
            }

            // if a modifier key is coming up on its own we should allow it
            if (action == 'keyup' && _isModifier(character)) {
                modifiers = [character];
            }

            // loop through all callbacks for the key that was pressed
            // and see if any of them match
            for (i = 0; i < self._callbacks[character].length; ++i) {
                callback = self._callbacks[character][i];

                // if a sequence name is not specified, but this is a sequence at
                // the wrong level then move onto the next match
                if (!sequenceName && callback.seq && _sequenceLevels[callback.seq] != callback.level) {
                    continue;
                }

                // if the action we are looking for doesn't match the action we got
                // then we should keep going
                if (action != callback.action) {
                    continue;
                }

                // if this is a keypress event and the meta key and control key
                // are not pressed that means that we need to only look at the
                // character, otherwise check the modifiers as well
                //
                // chrome will not fire a keypress if meta or control is down
                // safari will fire a keypress if meta or meta+shift is down
                // firefox will fire a keypress if meta or control is down
                if ((action == 'keypress' && !e.metaKey && !e.ctrlKey) || _modifiersMatch(modifiers, callback.modifiers)) {

                    // when you bind a combination or sequence a second time it
                    // should overwrite the first one.  if a sequenceName or
                    // combination is specified in this call it does just that
                    //
                    // @todo make deleting its own method?
                    var deleteCombo = !sequenceName && callback.combo == combination;
                    var deleteSequence = sequenceName && callback.seq == sequenceName && callback.level == level;
                    if (deleteCombo || deleteSequence) {
                        self._callbacks[character].splice(i, 1);
                    }

                    matches.push(callback);
                }
            }

            return matches;
        }

        /**
         * actually calls the callback function
         *
         * if your callback function returns false this will use the jquery
         * convention - prevent default and stop propogation on the event
         *
         * @param {Function} callback
         * @param {Event} e
         * @returns void
         */
        function _fireCallback(callback, e, combo, sequence) {

            // if this event should not happen stop here
            if (self.stopCallback(e, e.target || e.srcElement, combo, sequence)) {
                return;
            }

            if (callback(e, combo) === false) {
                _preventDefault(e);
                _stopPropagation(e);
            }
        }

        /**
         * handles a character key event
         *
         * @param {string} character
         * @param {Array} modifiers
         * @param {Event} e
         * @returns void
         */
        self._handleKey = function(character, modifiers, e) {
            var callbacks = _getMatches(character, modifiers, e);
            var i;
            var doNotReset = {};
            var maxLevel = 0;
            var processedSequenceCallback = false;

            // Calculate the maxLevel for sequences so we can only execute the longest callback sequence
            for (i = 0; i < callbacks.length; ++i) {
                if (callbacks[i].seq) {
                    maxLevel = Math.max(maxLevel, callbacks[i].level);
                }
            }

            // loop through matching callbacks for this key event
            for (i = 0; i < callbacks.length; ++i) {

                // fire for all sequence callbacks
                // this is because if for example you have multiple sequences
                // bound such as "g i" and "g t" they both need to fire the
                // callback for matching g cause otherwise you can only ever
                // match the first one
                if (callbacks[i].seq) {

                    // only fire callbacks for the maxLevel to prevent
                    // subsequences from also firing
                    //
                    // for example 'a option b' should not cause 'option b' to fire
                    // even though 'option b' is part of the other sequence
                    //
                    // any sequences that do not match here will be discarded
                    // below by the _resetSequences call
                    if (callbacks[i].level != maxLevel) {
                        continue;
                    }

                    processedSequenceCallback = true;

                    // keep a list of which sequences were matches for later
                    doNotReset[callbacks[i].seq] = 1;
                    _fireCallback(callbacks[i].callback, e, callbacks[i].combo, callbacks[i].seq);
                    continue;
                }

                // if there were no sequence matches but we are still here
                // that means this is a regular match so we should fire that
                if (!processedSequenceCallback) {
                    _fireCallback(callbacks[i].callback, e, callbacks[i].combo);
                }
            }

            // if the key you pressed matches the type of sequence without
            // being a modifier (ie "keyup" or "keypress") then we should
            // reset all sequences that were not matched by this event
            //
            // this is so, for example, if you have the sequence "h a t" and you
            // type "h e a r t" it does not match.  in this case the "e" will
            // cause the sequence to reset
            //
            // modifier keys are ignored because you can have a sequence
            // that contains modifiers such as "enter ctrl+space" and in most
            // cases the modifier key will be pressed before the next key
            //
            // also if you have a sequence such as "ctrl+b a" then pressing the
            // "b" key will trigger a "keypress" and a "keydown"
            //
            // the "keydown" is expected when there is a modifier, but the
            // "keypress" ends up matching the _nextExpectedAction since it occurs
            // after and that causes the sequence to reset
            //
            // we ignore keypresses in a sequence that directly follow a keydown
            // for the same character
            var ignoreThisKeypress = e.type == 'keypress' && _ignoreNextKeypress;
            if (e.type == _nextExpectedAction && !_isModifier(character) && !ignoreThisKeypress) {
                _resetSequences(doNotReset);
            }

            _ignoreNextKeypress = processedSequenceCallback && e.type == 'keydown';
        };

        /**
         * handles a keydown event
         *
         * @param {Event} e
         * @returns void
         */
        function _handleKeyEvent(e) {

            // normalize e.which for key events
            // @see http://stackoverflow.com/questions/4285627/javascript-keycode-vs-charcode-utter-confusion
            if (typeof e.which !== 'number') {
                e.which = e.keyCode;
            }

            var character = _characterFromEvent(e);

            // no character found then stop
            if (!character) {
                return;
            }

            // need to use === for the character check because the character can be 0
            if (e.type == 'keyup' && _ignoreNextKeyup === character) {
                _ignoreNextKeyup = false;
                return;
            }

            self.handleKey(character, _eventModifiers(e), e);
        }

        /**
         * called to set a 1 second timeout on the specified sequence
         *
         * this is so after each key press in the sequence you have 1 second
         * to press the next key before you have to start over
         *
         * @returns void
         */
        function _resetSequenceTimer() {
            clearTimeout(_resetTimer);
            _resetTimer = setTimeout(_resetSequences, 1000);
        }

        /**
         * binds a key sequence to an event
         *
         * @param {string} combo - combo specified in bind call
         * @param {Array} keys
         * @param {Function} callback
         * @param {string=} action
         * @returns void
         */
        function _bindSequence(combo, keys, callback, action) {

            // start off by adding a sequence level record for this combination
            // and setting the level to 0
            _sequenceLevels[combo] = 0;

            /**
             * callback to increase the sequence level for this sequence and reset
             * all other sequences that were active
             *
             * @param {string} nextAction
             * @returns {Function}
             */
            function _increaseSequence(nextAction) {
                return function() {
                    _nextExpectedAction = nextAction;
                    ++_sequenceLevels[combo];
                    _resetSequenceTimer();
                };
            }

            /**
             * wraps the specified callback inside of another function in order
             * to reset all sequence counters as soon as this sequence is done
             *
             * @param {Event} e
             * @returns void
             */
            function _callbackAndReset(e) {
                _fireCallback(callback, e, combo);

                // we should ignore the next key up if the action is key down
                // or keypress.  this is so if you finish a sequence and
                // release the key the final key will not trigger a keyup
                if (action !== 'keyup') {
                    _ignoreNextKeyup = _characterFromEvent(e);
                }

                // weird race condition if a sequence ends with the key
                // another sequence begins with
                setTimeout(_resetSequences, 10);
            }

            // loop through keys one at a time and bind the appropriate callback
            // function.  for any key leading up to the final one it should
            // increase the sequence. after the final, it should reset all sequences
            //
            // if an action is specified in the original bind call then that will
            // be used throughout.  otherwise we will pass the action that the
            // next key in the sequence should match.  this allows a sequence
            // to mix and match keypress and keydown events depending on which
            // ones are better suited to the key provided
            for (var i = 0; i < keys.length; ++i) {
                var isFinal = i + 1 === keys.length;
                var wrappedCallback = isFinal ? _callbackAndReset : _increaseSequence(action || _getKeyInfo(keys[i + 1]).action);
                _bindSingle(keys[i], wrappedCallback, action, combo, i);
            }
        }

        /**
         * binds a single keyboard combination
         *
         * @param {string} combination
         * @param {Function} callback
         * @param {string=} action
         * @param {string=} sequenceName - name of sequence if part of sequence
         * @param {number=} level - what part of the sequence the command is
         * @returns void
         */
        function _bindSingle(combination, callback, action, sequenceName, level) {

            // store a direct mapped reference for use with Mousetrap.trigger
            self._directMap[combination + ':' + action] = callback;

            // make sure multiple spaces in a row become a single space
            combination = combination.replace(/\s+/g, ' ');

            var sequence = combination.split(' ');
            var info;

            // if this pattern is a sequence of keys then run through this method
            // to reprocess each pattern one key at a time
            if (sequence.length > 1) {
                _bindSequence(combination, sequence, callback, action);
                return;
            }

            info = _getKeyInfo(combination, action);

            // make sure to initialize array if this is the first time
            // a callback is added for this key
            self._callbacks[info.key] = self._callbacks[info.key] || [];

            // remove an existing match if there is one
            _getMatches(info.key, info.modifiers, {type: info.action}, sequenceName, combination, level);

            // add this call back to the array
            // if it is a sequence put it at the beginning
            // if not put it at the end
            //
            // this is important because the way these are processed expects
            // the sequence ones to come first
            self._callbacks[info.key][sequenceName ? 'unshift' : 'push']({
                callback: callback,
                modifiers: info.modifiers,
                action: info.action,
                seq: sequenceName,
                level: level,
                combo: combination
            });
        }

        /**
         * binds multiple combinations to the same callback
         *
         * @param {Array} combinations
         * @param {Function} callback
         * @param {string|undefined} action
         * @returns void
         */
        self._bindMultiple = function(combinations, callback, action) {
            for (var i = 0; i < combinations.length; ++i) {
                _bindSingle(combinations[i], callback, action);
            }
        };

        // start!
        _addEvent(targetElement, 'keypress', _handleKeyEvent);
        _addEvent(targetElement, 'keydown', _handleKeyEvent);
        _addEvent(targetElement, 'keyup', _handleKeyEvent);
    }

    /**
     * binds an event to mousetrap
     *
     * can be a single key, a combination of keys separated with +,
     * an array of keys, or a sequence of keys separated by spaces
     *
     * be sure to list the modifier keys first to make sure that the
     * correct key ends up getting bound (the last key in the pattern)
     *
     * @param {string|Array} keys
     * @param {Function} callback
     * @param {string=} action - 'keypress', 'keydown', or 'keyup'
     * @returns void
     */
    Mousetrap.prototype.bind = function(keys, callback, action) {
        var self = this;
        keys = keys instanceof Array ? keys : [keys];
        self._bindMultiple.call(self, keys, callback, action);
        return self;
    };

    /**
     * unbinds an event to mousetrap
     *
     * the unbinding sets the callback function of the specified key combo
     * to an empty function and deletes the corresponding key in the
     * _directMap dict.
     *
     * TODO: actually remove this from the _callbacks dictionary instead
     * of binding an empty function
     *
     * the keycombo+action has to be exactly the same as
     * it was defined in the bind method
     *
     * @param {string|Array} keys
     * @param {string} action
     * @returns void
     */
    Mousetrap.prototype.unbind = function(keys, action) {
        var self = this;
        return self.bind.call(self, keys, function() {}, action);
    };

    /**
     * triggers an event that has already been bound
     *
     * @param {string} keys
     * @param {string=} action
     * @returns void
     */
    Mousetrap.prototype.trigger = function(keys, action) {
        var self = this;
        if (self._directMap[keys + ':' + action]) {
            self._directMap[keys + ':' + action]({}, keys);
        }
        return self;
    };

    /**
     * resets the library back to its initial state.  this is useful
     * if you want to clear out the current keyboard shortcuts and bind
     * new ones - for example if you switch to another page
     *
     * @returns void
     */
    Mousetrap.prototype.reset = function() {
        var self = this;
        self._callbacks = {};
        self._directMap = {};
        return self;
    };

    /**
     * should we stop this event before firing off callbacks
     *
     * @param {Event} e
     * @param {Element} element
     * @return {boolean}
     */
    Mousetrap.prototype.stopCallback = function(e, element) {
        var self = this;

        // if the element has the class "mousetrap" then no need to stop
        if ((' ' + element.className + ' ').indexOf(' mousetrap ') > -1) {
            return false;
        }

        if (_belongsTo(element, self.target)) {
            return false;
        }

        // stop for input, select, and textarea
        return element.tagName == 'INPUT' || element.tagName == 'SELECT' || element.tagName == 'TEXTAREA' || element.isContentEditable;
    };

    /**
     * exposes _handleKey publicly so it can be overwritten by extensions
     */
    Mousetrap.prototype.handleKey = function() {
        var self = this;
        return self._handleKey.apply(self, arguments);
    };

    /**
     * Init the global mousetrap functions
     *
     * This method is needed to allow the global mousetrap functions to work
     * now that mousetrap is a constructor function.
     */
    Mousetrap.init = function() {
        var documentMousetrap = Mousetrap(document);
        for (var method in documentMousetrap) {
            if (method.charAt(0) !== '_') {
                Mousetrap[method] = (function(method) {
                    return function() {
                        return documentMousetrap[method].apply(documentMousetrap, arguments);
                    };
                } (method));
            }
        }
    };

    Mousetrap.init();

    // expose mousetrap to the global object
    window.Mousetrap = Mousetrap;

    // expose as a common js module
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = Mousetrap;
    }

    // expose mousetrap as an AMD module
    if (typeof define === 'function' && define.amd) {
        define(function() {
            return Mousetrap;
        });
    }
}) (window, document);

},{}],200:[function(require,module,exports){
/**
 * adds a bindGlobal method to Mousetrap that allows you to
 * bind specific keyboard shortcuts that will still work
 * inside a text input field
 *
 * usage:
 * Mousetrap.bindGlobal('ctrl+s', _saveChanges);
 */
/* global Mousetrap:true */
(function(Mousetrap) {
    var _globalCallbacks = {};
    var _originalStopCallback = Mousetrap.prototype.stopCallback;

    Mousetrap.prototype.stopCallback = function(e, element, combo, sequence) {
        var self = this;

        if (self.paused) {
            return true;
        }

        if (_globalCallbacks[combo] || _globalCallbacks[sequence]) {
            return false;
        }

        return _originalStopCallback.call(self, e, element, combo);
    };

    Mousetrap.prototype.bindGlobal = function(keys, callback, action) {
        var self = this;
        self.bind(keys, callback, action);

        if (keys instanceof Array) {
            for (var i = 0; i < keys.length; i++) {
                _globalCallbacks[keys[i]] = true;
            }
            return;
        }

        _globalCallbacks[keys] = true;
    };

    Mousetrap.init();
}) (Mousetrap);

},{}],201:[function(require,module,exports){
/* NProgress, (c) 2013, 2014 Rico Sta. Cruz - http://ricostacruz.com/nprogress
 * @license MIT */

;(function(root, factory) {

  if (typeof define === 'function' && define.amd) {
    define(factory);
  } else if (typeof exports === 'object') {
    module.exports = factory();
  } else {
    root.NProgress = factory();
  }

})(this, function() {
  var NProgress = {};

  NProgress.version = '0.2.0';

  var Settings = NProgress.settings = {
    minimum: 0.08,
    easing: 'ease',
    positionUsing: '',
    speed: 200,
    trickle: true,
    trickleRate: 0.02,
    trickleSpeed: 800,
    showSpinner: true,
    barSelector: '[role="bar"]',
    spinnerSelector: '[role="spinner"]',
    parent: 'body',
    template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
  };

  /**
   * Updates configuration.
   *
   *     NProgress.configure({
   *       minimum: 0.1
   *     });
   */
  NProgress.configure = function(options) {
    var key, value;
    for (key in options) {
      value = options[key];
      if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
    }

    return this;
  };

  /**
   * Last number.
   */

  NProgress.status = null;

  /**
   * Sets the progress bar status, where `n` is a number from `0.0` to `1.0`.
   *
   *     NProgress.set(0.4);
   *     NProgress.set(1.0);
   */

  NProgress.set = function(n) {
    var started = NProgress.isStarted();

    n = clamp(n, Settings.minimum, 1);
    NProgress.status = (n === 1 ? null : n);

    var progress = NProgress.render(!started),
        bar      = progress.querySelector(Settings.barSelector),
        speed    = Settings.speed,
        ease     = Settings.easing;

    progress.offsetWidth; /* Repaint */

    queue(function(next) {
      // Set positionUsing if it hasn't already been set
      if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();

      // Add transition
      css(bar, barPositionCSS(n, speed, ease));

      if (n === 1) {
        // Fade out
        css(progress, { 
          transition: 'none', 
          opacity: 1 
        });
        progress.offsetWidth; /* Repaint */

        setTimeout(function() {
          css(progress, { 
            transition: 'all ' + speed + 'ms linear', 
            opacity: 0 
          });
          setTimeout(function() {
            NProgress.remove();
            next();
          }, speed);
        }, speed);
      } else {
        setTimeout(next, speed);
      }
    });

    return this;
  };

  NProgress.isStarted = function() {
    return typeof NProgress.status === 'number';
  };

  /**
   * Shows the progress bar.
   * This is the same as setting the status to 0%, except that it doesn't go backwards.
   *
   *     NProgress.start();
   *
   */
  NProgress.start = function() {
    if (!NProgress.status) NProgress.set(0);

    var work = function() {
      setTimeout(function() {
        if (!NProgress.status) return;
        NProgress.trickle();
        work();
      }, Settings.trickleSpeed);
    };

    if (Settings.trickle) work();

    return this;
  };

  /**
   * Hides the progress bar.
   * This is the *sort of* the same as setting the status to 100%, with the
   * difference being `done()` makes some placebo effect of some realistic motion.
   *
   *     NProgress.done();
   *
   * If `true` is passed, it will show the progress bar even if its hidden.
   *
   *     NProgress.done(true);
   */

  NProgress.done = function(force) {
    if (!force && !NProgress.status) return this;

    return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
  };

  /**
   * Increments by a random amount.
   */

  NProgress.inc = function(amount) {
    var n = NProgress.status;

    if (!n) {
      return NProgress.start();
    } else {
      if (typeof amount !== 'number') {
        amount = (1 - n) * clamp(Math.random() * n, 0.1, 0.95);
      }

      n = clamp(n + amount, 0, 0.994);
      return NProgress.set(n);
    }
  };

  NProgress.trickle = function() {
    return NProgress.inc(Math.random() * Settings.trickleRate);
  };

  /**
   * Waits for all supplied jQuery promises and
   * increases the progress as the promises resolve.
   *
   * @param $promise jQUery Promise
   */
  (function() {
    var initial = 0, current = 0;

    NProgress.promise = function($promise) {
      if (!$promise || $promise.state() === "resolved") {
        return this;
      }

      if (current === 0) {
        NProgress.start();
      }

      initial++;
      current++;

      $promise.always(function() {
        current--;
        if (current === 0) {
            initial = 0;
            NProgress.done();
        } else {
            NProgress.set((initial - current) / initial);
        }
      });

      return this;
    };

  })();

  /**
   * (Internal) renders the progress bar markup based on the `template`
   * setting.
   */

  NProgress.render = function(fromStart) {
    if (NProgress.isRendered()) return document.getElementById('nprogress');

    addClass(document.documentElement, 'nprogress-busy');
    
    var progress = document.createElement('div');
    progress.id = 'nprogress';
    progress.innerHTML = Settings.template;

    var bar      = progress.querySelector(Settings.barSelector),
        perc     = fromStart ? '-100' : toBarPerc(NProgress.status || 0),
        parent   = document.querySelector(Settings.parent),
        spinner;
    
    css(bar, {
      transition: 'all 0 linear',
      transform: 'translate3d(' + perc + '%,0,0)'
    });

    if (!Settings.showSpinner) {
      spinner = progress.querySelector(Settings.spinnerSelector);
      spinner && removeElement(spinner);
    }

    if (parent != document.body) {
      addClass(parent, 'nprogress-custom-parent');
    }

    parent.appendChild(progress);
    return progress;
  };

  /**
   * Removes the element. Opposite of render().
   */

  NProgress.remove = function() {
    removeClass(document.documentElement, 'nprogress-busy');
    removeClass(document.querySelector(Settings.parent), 'nprogress-custom-parent');
    var progress = document.getElementById('nprogress');
    progress && removeElement(progress);
  };

  /**
   * Checks if the progress bar is rendered.
   */

  NProgress.isRendered = function() {
    return !!document.getElementById('nprogress');
  };

  /**
   * Determine which positioning CSS rule to use.
   */

  NProgress.getPositioningCSS = function() {
    // Sniff on document.body.style
    var bodyStyle = document.body.style;

    // Sniff prefixes
    var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
                       ('MozTransform' in bodyStyle) ? 'Moz' :
                       ('msTransform' in bodyStyle) ? 'ms' :
                       ('OTransform' in bodyStyle) ? 'O' : '';

    if (vendorPrefix + 'Perspective' in bodyStyle) {
      // Modern browsers with 3D support, e.g. Webkit, IE10
      return 'translate3d';
    } else if (vendorPrefix + 'Transform' in bodyStyle) {
      // Browsers without 3D support, e.g. IE9
      return 'translate';
    } else {
      // Browsers without translate() support, e.g. IE7-8
      return 'margin';
    }
  };

  /**
   * Helpers
   */

  function clamp(n, min, max) {
    if (n < min) return min;
    if (n > max) return max;
    return n;
  }

  /**
   * (Internal) converts a percentage (`0..1`) to a bar translateX
   * percentage (`-100%..0%`).
   */

  function toBarPerc(n) {
    return (-1 + n) * 100;
  }


  /**
   * (Internal) returns the correct CSS for changing the bar's
   * position given an n percentage, and speed and ease from Settings
   */

  function barPositionCSS(n, speed, ease) {
    var barCSS;

    if (Settings.positionUsing === 'translate3d') {
      barCSS = { transform: 'translate3d('+toBarPerc(n)+'%,0,0)' };
    } else if (Settings.positionUsing === 'translate') {
      barCSS = { transform: 'translate('+toBarPerc(n)+'%,0)' };
    } else {
      barCSS = { 'margin-left': toBarPerc(n)+'%' };
    }

    barCSS.transition = 'all '+speed+'ms '+ease;

    return barCSS;
  }

  /**
   * (Internal) Queues a function to be executed.
   */

  var queue = (function() {
    var pending = [];
    
    function next() {
      var fn = pending.shift();
      if (fn) {
        fn(next);
      }
    }

    return function(fn) {
      pending.push(fn);
      if (pending.length == 1) next();
    };
  })();

  /**
   * (Internal) Applies css properties to an element, similar to the jQuery 
   * css method.
   *
   * While this helper does assist with vendor prefixed property names, it 
   * does not perform any manipulation of values prior to setting styles.
   */

  var css = (function() {
    var cssPrefixes = [ 'Webkit', 'O', 'Moz', 'ms' ],
        cssProps    = {};

    function camelCase(string) {
      return string.replace(/^-ms-/, 'ms-').replace(/-([\da-z])/gi, function(match, letter) {
        return letter.toUpperCase();
      });
    }

    function getVendorProp(name) {
      var style = document.body.style;
      if (name in style) return name;

      var i = cssPrefixes.length,
          capName = name.charAt(0).toUpperCase() + name.slice(1),
          vendorName;
      while (i--) {
        vendorName = cssPrefixes[i] + capName;
        if (vendorName in style) return vendorName;
      }

      return name;
    }

    function getStyleProp(name) {
      name = camelCase(name);
      return cssProps[name] || (cssProps[name] = getVendorProp(name));
    }

    function applyCss(element, prop, value) {
      prop = getStyleProp(prop);
      element.style[prop] = value;
    }

    return function(element, properties) {
      var args = arguments,
          prop, 
          value;

      if (args.length == 2) {
        for (prop in properties) {
          value = properties[prop];
          if (value !== undefined && properties.hasOwnProperty(prop)) applyCss(element, prop, value);
        }
      } else {
        applyCss(element, args[1], args[2]);
      }
    }
  })();

  /**
   * (Internal) Determines if an element or space separated list of class names contains a class name.
   */

  function hasClass(element, name) {
    var list = typeof element == 'string' ? element : classList(element);
    return list.indexOf(' ' + name + ' ') >= 0;
  }

  /**
   * (Internal) Adds a class to an element.
   */

  function addClass(element, name) {
    var oldList = classList(element),
        newList = oldList + name;

    if (hasClass(oldList, name)) return; 

    // Trim the opening space.
    element.className = newList.substring(1);
  }

  /**
   * (Internal) Removes a class from an element.
   */

  function removeClass(element, name) {
    var oldList = classList(element),
        newList;

    if (!hasClass(element, name)) return;

    // Replace the class name.
    newList = oldList.replace(' ' + name + ' ', ' ');

    // Trim the opening and closing spaces.
    element.className = newList.substring(1, newList.length - 1);
  }

  /**
   * (Internal) Gets a space separated list of the class names on the element. 
   * The list is wrapped with a single space on each end to facilitate finding 
   * matches within the list.
   */

  function classList(element) {
    return (' ' + (element.className || '') + ' ').replace(/\s+/gi, ' ');
  }

  /**
   * (Internal) Removes an element from the DOM.
   */

  function removeElement(element) {
    element && element.parentNode && element.parentNode.removeChild(element);
  }

  return NProgress;
});


},{}],202:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

module.exports = require('./src/js/adaptor/jquery');

},{"./src/js/adaptor/jquery":203}],203:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var ps = require('../main')
  , psInstances = require('../plugin/instances');

function mountJQuery(jQuery) {
  jQuery.fn.perfectScrollbar = function (settingOrCommand) {
    return this.each(function () {
      if (typeof settingOrCommand === 'object' ||
          typeof settingOrCommand === 'undefined') {
        // If it's an object or none, initialize.
        var settings = settingOrCommand;

        if (!psInstances.get(this)) {
          ps.initialize(this, settings);
        }
      } else {
        // Unless, it may be a command.
        var command = settingOrCommand;

        if (command === 'update') {
          ps.update(this);
        } else if (command === 'destroy') {
          ps.destroy(this);
        }
      }

      return jQuery(this);
    });
  };
}

if (typeof define === 'function' && define.amd) {
  // AMD. Register as an anonymous module.
  define(['jquery'], mountJQuery);
} else {
  var jq = window.jQuery ? window.jQuery : window.$;
  if (typeof jq !== 'undefined') {
    mountJQuery(jq);
  }
}

module.exports = mountJQuery;

},{"../main":209,"../plugin/instances":220}],204:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

function oldAdd(element, className) {
  var classes = element.className.split(' ');
  if (classes.indexOf(className) < 0) {
    classes.push(className);
  }
  element.className = classes.join(' ');
}

function oldRemove(element, className) {
  var classes = element.className.split(' ');
  var idx = classes.indexOf(className);
  if (idx >= 0) {
    classes.splice(idx, 1);
  }
  element.className = classes.join(' ');
}

exports.add = function (element, className) {
  if (element.classList) {
    element.classList.add(className);
  } else {
    oldAdd(element, className);
  }
};

exports.remove = function (element, className) {
  if (element.classList) {
    element.classList.remove(className);
  } else {
    oldRemove(element, className);
  }
};

exports.list = function (element) {
  if (element.classList) {
    return Array.prototype.slice.apply(element.classList);
  } else {
    return element.className.split(' ');
  }
};

},{}],205:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var DOM = {};

DOM.e = function (tagName, className) {
  var element = document.createElement(tagName);
  element.className = className;
  return element;
};

DOM.appendTo = function (child, parent) {
  parent.appendChild(child);
  return child;
};

function cssGet(element, styleName) {
  return window.getComputedStyle(element)[styleName];
}

function cssSet(element, styleName, styleValue) {
  if (typeof styleValue === 'number') {
    styleValue = styleValue.toString() + 'px';
  }
  element.style[styleName] = styleValue;
  return element;
}

function cssMultiSet(element, obj) {
  for (var key in obj) {
    var val = obj[key];
    if (typeof val === 'number') {
      val = val.toString() + 'px';
    }
    element.style[key] = val;
  }
  return element;
}

DOM.css = function (element, styleNameOrObject, styleValue) {
  if (typeof styleNameOrObject === 'object') {
    // multiple set with object
    return cssMultiSet(element, styleNameOrObject);
  } else {
    if (typeof styleValue === 'undefined') {
      return cssGet(element, styleNameOrObject);
    } else {
      return cssSet(element, styleNameOrObject, styleValue);
    }
  }
};

DOM.matches = function (element, query) {
  if (typeof element.matches !== 'undefined') {
    return element.matches(query);
  } else {
    if (typeof element.matchesSelector !== 'undefined') {
      return element.matchesSelector(query);
    } else if (typeof element.webkitMatchesSelector !== 'undefined') {
      return element.webkitMatchesSelector(query);
    } else if (typeof element.mozMatchesSelector !== 'undefined') {
      return element.mozMatchesSelector(query);
    } else if (typeof element.msMatchesSelector !== 'undefined') {
      return element.msMatchesSelector(query);
    }
  }
};

DOM.remove = function (element) {
  if (typeof element.remove !== 'undefined') {
    element.remove();
  } else {
    if (element.parentNode) {
      element.parentNode.removeChild(element);
    }
  }
};

DOM.queryChildren = function (element, selector) {
  return Array.prototype.filter.call(element.childNodes, function (child) {
    return DOM.matches(child, selector);
  });
};

module.exports = DOM;

},{}],206:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var EventElement = function (element) {
  this.element = element;
  this.events = {};
};

EventElement.prototype.bind = function (eventName, handler) {
  if (typeof this.events[eventName] === 'undefined') {
    this.events[eventName] = [];
  }
  this.events[eventName].push(handler);
  this.element.addEventListener(eventName, handler, false);
};

EventElement.prototype.unbind = function (eventName, handler) {
  var isHandlerProvided = (typeof handler !== 'undefined');
  this.events[eventName] = this.events[eventName].filter(function (hdlr) {
    if (isHandlerProvided && hdlr !== handler) {
      return true;
    }
    this.element.removeEventListener(eventName, hdlr, false);
    return false;
  }, this);
};

EventElement.prototype.unbindAll = function () {
  for (var name in this.events) {
    this.unbind(name);
  }
};

var EventManager = function () {
  this.eventElements = [];
};

EventManager.prototype.eventElement = function (element) {
  var ee = this.eventElements.filter(function (eventElement) {
    return eventElement.element === element;
  })[0];
  if (typeof ee === 'undefined') {
    ee = new EventElement(element);
    this.eventElements.push(ee);
  }
  return ee;
};

EventManager.prototype.bind = function (element, eventName, handler) {
  this.eventElement(element).bind(eventName, handler);
};

EventManager.prototype.unbind = function (element, eventName, handler) {
  this.eventElement(element).unbind(eventName, handler);
};

EventManager.prototype.unbindAll = function () {
  for (var i = 0; i < this.eventElements.length; i++) {
    this.eventElements[i].unbindAll();
  }
};

EventManager.prototype.once = function (element, eventName, handler) {
  var ee = this.eventElement(element);
  var onceHandler = function (e) {
    ee.unbind(eventName, onceHandler);
    handler(e);
  };
  ee.bind(eventName, onceHandler);
};

module.exports = EventManager;

},{}],207:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

module.exports = (function () {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function () {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

},{}],208:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var cls = require('./class')
  , d = require('./dom');

exports.toInt = function (x) {
  return parseInt(x, 10) || 0;
};

exports.clone = function (obj) {
  if (obj === null) {
    return null;
  } else if (typeof obj === 'object') {
    var result = {};
    for (var key in obj) {
      result[key] = this.clone(obj[key]);
    }
    return result;
  } else {
    return obj;
  }
};

exports.extend = function (original, source) {
  var result = this.clone(original);
  for (var key in source) {
    result[key] = this.clone(source[key]);
  }
  return result;
};

exports.isEditable = function (el) {
  return d.matches(el, "input,[contenteditable]") ||
         d.matches(el, "select,[contenteditable]") ||
         d.matches(el, "textarea,[contenteditable]") ||
         d.matches(el, "button,[contenteditable]");
};

exports.removePsClasses = function (element) {
  var clsList = cls.list(element);
  for (var i = 0; i < clsList.length; i++) {
    var className = clsList[i];
    if (className.indexOf('ps-') === 0) {
      cls.remove(element, className);
    }
  }
};

exports.outerWidth = function (element) {
  return this.toInt(d.css(element, 'width')) +
         this.toInt(d.css(element, 'paddingLeft')) +
         this.toInt(d.css(element, 'paddingRight')) +
         this.toInt(d.css(element, 'borderLeftWidth')) +
         this.toInt(d.css(element, 'borderRightWidth'));
};

exports.startScrolling = function (element, axis) {
  cls.add(element, 'ps-in-scrolling');
  if (typeof axis !== 'undefined') {
    cls.add(element, 'ps-' + axis);
  } else {
    cls.add(element, 'ps-x');
    cls.add(element, 'ps-y');
  }
};

exports.stopScrolling = function (element, axis) {
  cls.remove(element, 'ps-in-scrolling');
  if (typeof axis !== 'undefined') {
    cls.remove(element, 'ps-' + axis);
  } else {
    cls.remove(element, 'ps-x');
    cls.remove(element, 'ps-y');
  }
};

exports.env = {
  isWebKit: 'WebkitAppearance' in document.documentElement.style,
  supportsTouch: (('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch),
  supportsIePointer: window.navigator.msMaxTouchPoints !== null
};

},{"./class":204,"./dom":205}],209:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var destroy = require('./plugin/destroy')
  , initialize = require('./plugin/initialize')
  , update = require('./plugin/update');

module.exports = {
  initialize: initialize,
  update: update,
  destroy: destroy
};

},{"./plugin/destroy":211,"./plugin/initialize":219,"./plugin/update":223}],210:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

module.exports = {
  maxScrollbarLength: null,
  minScrollbarLength: null,
  scrollXMarginOffset: 0,
  scrollYMarginOffset: 0,
  stopPropagationOnClick: true,
  suppressScrollX: false,
  suppressScrollY: false,
  swipePropagation: true,
  useBothWheelAxes: false,
  useKeyboard: true,
  useSelectionScroll: false,
  wheelPropagation: false,
  wheelSpeed: 1
};

},{}],211:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var d = require('../lib/dom')
  , h = require('../lib/helper')
  , instances = require('./instances');

module.exports = function (element) {
  var i = instances.get(element);

  if (!i) {
    return;
  }

  i.event.unbindAll();
  d.remove(i.scrollbarX);
  d.remove(i.scrollbarY);
  d.remove(i.scrollbarXRail);
  d.remove(i.scrollbarYRail);
  h.removePsClasses(element);

  instances.remove(element);
};

},{"../lib/dom":205,"../lib/helper":208,"./instances":220}],212:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var h = require('../../lib/helper')
  , instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindClickRailHandler(element, i) {
  function pageOffset(el) {
    return el.getBoundingClientRect();
  }
  var stopPropagation = window.Event.prototype.stopPropagation.bind;

  if (i.settings.stopPropagationOnClick) {
    i.event.bind(i.scrollbarY, 'click', stopPropagation);
  }
  i.event.bind(i.scrollbarYRail, 'click', function (e) {
    var halfOfScrollbarLength = h.toInt(i.scrollbarYHeight / 2);
    var positionTop = i.railYRatio * (e.pageY - window.pageYOffset - pageOffset(i.scrollbarYRail).top - halfOfScrollbarLength);
    var maxPositionTop = i.railYRatio * (i.railYHeight - i.scrollbarYHeight);
    var positionRatio = positionTop / maxPositionTop;

    if (positionRatio < 0) {
      positionRatio = 0;
    } else if (positionRatio > 1) {
      positionRatio = 1;
    }

    updateScroll(element, 'top', (i.contentHeight - i.containerHeight) * positionRatio);
    updateGeometry(element);

    e.stopPropagation();
  });

  if (i.settings.stopPropagationOnClick) {
    i.event.bind(i.scrollbarX, 'click', stopPropagation);
  }
  i.event.bind(i.scrollbarXRail, 'click', function (e) {
    var halfOfScrollbarLength = h.toInt(i.scrollbarXWidth / 2);
    var positionLeft = i.railXRatio * (e.pageX - window.pageXOffset - pageOffset(i.scrollbarXRail).left - halfOfScrollbarLength);
    var maxPositionLeft = i.railXRatio * (i.railXWidth - i.scrollbarXWidth);
    var positionRatio = positionLeft / maxPositionLeft;

    if (positionRatio < 0) {
      positionRatio = 0;
    } else if (positionRatio > 1) {
      positionRatio = 1;
    }

    updateScroll(element, 'left', ((i.contentWidth - i.containerWidth) * positionRatio) - i.negativeScrollAdjustment);
    updateGeometry(element);

    e.stopPropagation();
  });
}

module.exports = function (element) {
  var i = instances.get(element);
  bindClickRailHandler(element, i);
};

},{"../../lib/helper":208,"../instances":220,"../update-geometry":221,"../update-scroll":222}],213:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var d = require('../../lib/dom')
  , h = require('../../lib/helper')
  , instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindMouseScrollXHandler(element, i) {
  var currentLeft = null;
  var currentPageX = null;

  function updateScrollLeft(deltaX) {
    var newLeft = currentLeft + (deltaX * i.railXRatio);
    var maxLeft = Math.max(0, i.scrollbarXRail.getBoundingClientRect().left) + (i.railXRatio * (i.railXWidth - i.scrollbarXWidth));

    if (newLeft < 0) {
      i.scrollbarXLeft = 0;
    } else if (newLeft > maxLeft) {
      i.scrollbarXLeft = maxLeft;
    } else {
      i.scrollbarXLeft = newLeft;
    }

    var scrollLeft = h.toInt(i.scrollbarXLeft * (i.contentWidth - i.containerWidth) / (i.containerWidth - (i.railXRatio * i.scrollbarXWidth))) - i.negativeScrollAdjustment;
    updateScroll(element, 'left', scrollLeft);
  }

  var mouseMoveHandler = function (e) {
    updateScrollLeft(e.pageX - currentPageX);
    updateGeometry(element);
    e.stopPropagation();
    e.preventDefault();
  };

  var mouseUpHandler = function () {
    h.stopScrolling(element, 'x');
    i.event.unbind(i.ownerDocument, 'mousemove', mouseMoveHandler);
  };

  i.event.bind(i.scrollbarX, 'mousedown', function (e) {
    currentPageX = e.pageX;
    currentLeft = h.toInt(d.css(i.scrollbarX, 'left')) * i.railXRatio;
    h.startScrolling(element, 'x');

    i.event.bind(i.ownerDocument, 'mousemove', mouseMoveHandler);
    i.event.once(i.ownerDocument, 'mouseup', mouseUpHandler);

    e.stopPropagation();
    e.preventDefault();
  });
}

function bindMouseScrollYHandler(element, i) {
  var currentTop = null;
  var currentPageY = null;

  function updateScrollTop(deltaY) {
    var newTop = currentTop + (deltaY * i.railYRatio);
    var maxTop = Math.max(0, i.scrollbarYRail.getBoundingClientRect().top) + (i.railYRatio * (i.railYHeight - i.scrollbarYHeight));

    if (newTop < 0) {
      i.scrollbarYTop = 0;
    } else if (newTop > maxTop) {
      i.scrollbarYTop = maxTop;
    } else {
      i.scrollbarYTop = newTop;
    }

    var scrollTop = h.toInt(i.scrollbarYTop * (i.contentHeight - i.containerHeight) / (i.containerHeight - (i.railYRatio * i.scrollbarYHeight)));
    updateScroll(element, 'top', scrollTop);
  }

  var mouseMoveHandler = function (e) {
    updateScrollTop(e.pageY - currentPageY);
    updateGeometry(element);
    e.stopPropagation();
    e.preventDefault();
  };

  var mouseUpHandler = function () {
    h.stopScrolling(element, 'y');
    i.event.unbind(i.ownerDocument, 'mousemove', mouseMoveHandler);
  };

  i.event.bind(i.scrollbarY, 'mousedown', function (e) {
    currentPageY = e.pageY;
    currentTop = h.toInt(d.css(i.scrollbarY, 'top')) * i.railYRatio;
    h.startScrolling(element, 'y');

    i.event.bind(i.ownerDocument, 'mousemove', mouseMoveHandler);
    i.event.once(i.ownerDocument, 'mouseup', mouseUpHandler);

    e.stopPropagation();
    e.preventDefault();
  });
}

module.exports = function (element) {
  var i = instances.get(element);
  bindMouseScrollXHandler(element, i);
  bindMouseScrollYHandler(element, i);
};

},{"../../lib/dom":205,"../../lib/helper":208,"../instances":220,"../update-geometry":221,"../update-scroll":222}],214:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var h = require('../../lib/helper')
  , instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindKeyboardHandler(element, i) {
  var hovered = false;
  i.event.bind(element, 'mouseenter', function () {
    hovered = true;
  });
  i.event.bind(element, 'mouseleave', function () {
    hovered = false;
  });

  var shouldPrevent = false;
  function shouldPreventDefault(deltaX, deltaY) {
    var scrollTop = element.scrollTop;
    if (deltaX === 0) {
      if (!i.scrollbarYActive) {
        return false;
      }
      if ((scrollTop === 0 && deltaY > 0) || (scrollTop >= i.contentHeight - i.containerHeight && deltaY < 0)) {
        return !i.settings.wheelPropagation;
      }
    }

    var scrollLeft = element.scrollLeft;
    if (deltaY === 0) {
      if (!i.scrollbarXActive) {
        return false;
      }
      if ((scrollLeft === 0 && deltaX < 0) || (scrollLeft >= i.contentWidth - i.containerWidth && deltaX > 0)) {
        return !i.settings.wheelPropagation;
      }
    }
    return true;
  }

  i.event.bind(i.ownerDocument, 'keydown', function (e) {
    if (e.isDefaultPrevented && e.isDefaultPrevented()) {
      return;
    }

    if (!hovered) {
      return;
    }

    var activeElement = document.activeElement ? document.activeElement : i.ownerDocument.activeElement;
    if (activeElement) {
      // go deeper if element is a webcomponent
      while (activeElement.shadowRoot) {
        activeElement = activeElement.shadowRoot.activeElement;
      }
      if (h.isEditable(activeElement)) {
        return;
      }
    }

    var deltaX = 0;
    var deltaY = 0;

    switch (e.which) {
    case 37: // left
      deltaX = -30;
      break;
    case 38: // up
      deltaY = 30;
      break;
    case 39: // right
      deltaX = 30;
      break;
    case 40: // down
      deltaY = -30;
      break;
    case 33: // page up
      deltaY = 90;
      break;
    case 32: // space bar
      if (e.shiftKey) {
        deltaY = 90;
      } else {
        deltaY = -90;
      }
      break;
    case 34: // page down
      deltaY = -90;
      break;
    case 35: // end
      if (e.ctrlKey) {
        deltaY = -i.contentHeight;
      } else {
        deltaY = -i.containerHeight;
      }
      break;
    case 36: // home
      if (e.ctrlKey) {
        deltaY = element.scrollTop;
      } else {
        deltaY = i.containerHeight;
      }
      break;
    default:
      return;
    }

    updateScroll(element, 'top', element.scrollTop - deltaY);
    updateScroll(element, 'left', element.scrollLeft + deltaX);
    updateGeometry(element);

    shouldPrevent = shouldPreventDefault(deltaX, deltaY);
    if (shouldPrevent) {
      e.preventDefault();
    }
  });
}

module.exports = function (element) {
  var i = instances.get(element);
  bindKeyboardHandler(element, i);
};

},{"../../lib/helper":208,"../instances":220,"../update-geometry":221,"../update-scroll":222}],215:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindMouseWheelHandler(element, i) {
  var shouldPrevent = false;

  function shouldPreventDefault(deltaX, deltaY) {
    var scrollTop = element.scrollTop;
    if (deltaX === 0) {
      if (!i.scrollbarYActive) {
        return false;
      }
      if ((scrollTop === 0 && deltaY > 0) || (scrollTop >= i.contentHeight - i.containerHeight && deltaY < 0)) {
        return !i.settings.wheelPropagation;
      }
    }

    var scrollLeft = element.scrollLeft;
    if (deltaY === 0) {
      if (!i.scrollbarXActive) {
        return false;
      }
      if ((scrollLeft === 0 && deltaX < 0) || (scrollLeft >= i.contentWidth - i.containerWidth && deltaX > 0)) {
        return !i.settings.wheelPropagation;
      }
    }
    return true;
  }

  function getDeltaFromEvent(e) {
    var deltaX = e.deltaX;
    var deltaY = -1 * e.deltaY;

    if (typeof deltaX === "undefined" || typeof deltaY === "undefined") {
      // OS X Safari
      deltaX = -1 * e.wheelDeltaX / 6;
      deltaY = e.wheelDeltaY / 6;
    }

    if (e.deltaMode && e.deltaMode === 1) {
      // Firefox in deltaMode 1: Line scrolling
      deltaX *= 10;
      deltaY *= 10;
    }

    if (deltaX !== deltaX && deltaY !== deltaY/* NaN checks */) {
      // IE in some mouse drivers
      deltaX = 0;
      deltaY = e.wheelDelta;
    }

    return [deltaX, deltaY];
  }

  function shouldBeConsumedByTextarea(deltaX, deltaY) {
    var hoveredTextarea = element.querySelector('textarea:hover');
    if (hoveredTextarea) {
      var maxScrollTop = hoveredTextarea.scrollHeight - hoveredTextarea.clientHeight;
      if (maxScrollTop > 0) {
        if (!(hoveredTextarea.scrollTop === 0 && deltaY > 0) &&
            !(hoveredTextarea.scrollTop === maxScrollTop && deltaY < 0)) {
          return true;
        }
      }
      var maxScrollLeft = hoveredTextarea.scrollLeft - hoveredTextarea.clientWidth;
      if (maxScrollLeft > 0) {
        if (!(hoveredTextarea.scrollLeft === 0 && deltaX < 0) &&
            !(hoveredTextarea.scrollLeft === maxScrollLeft && deltaX > 0)) {
          return true;
        }
      }
    }
    return false;
  }

  function mousewheelHandler(e) {
    var delta = getDeltaFromEvent(e);

    var deltaX = delta[0];
    var deltaY = delta[1];

    if (shouldBeConsumedByTextarea(deltaX, deltaY)) {
      return;
    }

    shouldPrevent = false;
    if (!i.settings.useBothWheelAxes) {
      // deltaX will only be used for horizontal scrolling and deltaY will
      // only be used for vertical scrolling - this is the default
      updateScroll(element, 'top', element.scrollTop - (deltaY * i.settings.wheelSpeed));
      updateScroll(element, 'left', element.scrollLeft + (deltaX * i.settings.wheelSpeed));
    } else if (i.scrollbarYActive && !i.scrollbarXActive) {
      // only vertical scrollbar is active and useBothWheelAxes option is
      // active, so let's scroll vertical bar using both mouse wheel axes
      if (deltaY) {
        updateScroll(element, 'top', element.scrollTop - (deltaY * i.settings.wheelSpeed));
      } else {
        updateScroll(element, 'top', element.scrollTop + (deltaX * i.settings.wheelSpeed));
      }
      shouldPrevent = true;
    } else if (i.scrollbarXActive && !i.scrollbarYActive) {
      // useBothWheelAxes and only horizontal bar is active, so use both
      // wheel axes for horizontal bar
      if (deltaX) {
        updateScroll(element, 'left', element.scrollLeft + (deltaX * i.settings.wheelSpeed));
      } else {
        updateScroll(element, 'left', element.scrollLeft - (deltaY * i.settings.wheelSpeed));
      }
      shouldPrevent = true;
    }

    updateGeometry(element);

    shouldPrevent = (shouldPrevent || shouldPreventDefault(deltaX, deltaY));
    if (shouldPrevent) {
      e.stopPropagation();
      e.preventDefault();
    }
  }

  if (typeof window.onwheel !== "undefined") {
    i.event.bind(element, 'wheel', mousewheelHandler);
  } else if (typeof window.onmousewheel !== "undefined") {
    i.event.bind(element, 'mousewheel', mousewheelHandler);
  }
}

module.exports = function (element) {
  var i = instances.get(element);
  bindMouseWheelHandler(element, i);
};

},{"../instances":220,"../update-geometry":221,"../update-scroll":222}],216:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var instances = require('../instances')
  , updateGeometry = require('../update-geometry');

function bindNativeScrollHandler(element, i) {
  i.event.bind(element, 'scroll', function () {
    updateGeometry(element);
  });
}

module.exports = function (element) {
  var i = instances.get(element);
  bindNativeScrollHandler(element, i);
};

},{"../instances":220,"../update-geometry":221}],217:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var h = require('../../lib/helper')
  , instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindSelectionHandler(element, i) {
  function getRangeNode() {
    var selection = window.getSelection ? window.getSelection() :
                    document.getSelection ? document.getSelection() : '';
    if (selection.toString().length === 0) {
      return null;
    } else {
      return selection.getRangeAt(0).commonAncestorContainer;
    }
  }

  var scrollingLoop = null;
  var scrollDiff = {top: 0, left: 0};
  function startScrolling() {
    if (!scrollingLoop) {
      scrollingLoop = setInterval(function () {
        if (!instances.get(element)) {
          clearInterval(scrollingLoop);
          return;
        }

        updateScroll(element, 'top', element.scrollTop + scrollDiff.top);
        updateScroll(element, 'left', element.scrollLeft + scrollDiff.left);
        updateGeometry(element);
      }, 50); // every .1 sec
    }
  }
  function stopScrolling() {
    if (scrollingLoop) {
      clearInterval(scrollingLoop);
      scrollingLoop = null;
    }
    h.stopScrolling(element);
  }

  var isSelected = false;
  i.event.bind(i.ownerDocument, 'selectionchange', function () {
    if (element.contains(getRangeNode())) {
      isSelected = true;
    } else {
      isSelected = false;
      stopScrolling();
    }
  });
  i.event.bind(window, 'mouseup', function () {
    if (isSelected) {
      isSelected = false;
      stopScrolling();
    }
  });

  i.event.bind(window, 'mousemove', function (e) {
    if (isSelected) {
      var mousePosition = {x: e.pageX, y: e.pageY};
      var containerGeometry = {
        left: element.offsetLeft,
        right: element.offsetLeft + element.offsetWidth,
        top: element.offsetTop,
        bottom: element.offsetTop + element.offsetHeight
      };

      if (mousePosition.x < containerGeometry.left + 3) {
        scrollDiff.left = -5;
        h.startScrolling(element, 'x');
      } else if (mousePosition.x > containerGeometry.right - 3) {
        scrollDiff.left = 5;
        h.startScrolling(element, 'x');
      } else {
        scrollDiff.left = 0;
      }

      if (mousePosition.y < containerGeometry.top + 3) {
        if (containerGeometry.top + 3 - mousePosition.y < 5) {
          scrollDiff.top = -5;
        } else {
          scrollDiff.top = -20;
        }
        h.startScrolling(element, 'y');
      } else if (mousePosition.y > containerGeometry.bottom - 3) {
        if (mousePosition.y - containerGeometry.bottom + 3 < 5) {
          scrollDiff.top = 5;
        } else {
          scrollDiff.top = 20;
        }
        h.startScrolling(element, 'y');
      } else {
        scrollDiff.top = 0;
      }

      if (scrollDiff.top === 0 && scrollDiff.left === 0) {
        stopScrolling();
      } else {
        startScrolling();
      }
    }
  });
}

module.exports = function (element) {
  var i = instances.get(element);
  bindSelectionHandler(element, i);
};

},{"../../lib/helper":208,"../instances":220,"../update-geometry":221,"../update-scroll":222}],218:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var instances = require('../instances')
  , updateGeometry = require('../update-geometry')
  , updateScroll = require('../update-scroll');

function bindTouchHandler(element, i, supportsTouch, supportsIePointer) {
  function shouldPreventDefault(deltaX, deltaY) {
    var scrollTop = element.scrollTop;
    var scrollLeft = element.scrollLeft;
    var magnitudeX = Math.abs(deltaX);
    var magnitudeY = Math.abs(deltaY);

    if (magnitudeY > magnitudeX) {
      // user is perhaps trying to swipe up/down the page

      if (((deltaY < 0) && (scrollTop === i.contentHeight - i.containerHeight)) ||
          ((deltaY > 0) && (scrollTop === 0))) {
        return !i.settings.swipePropagation;
      }
    } else if (magnitudeX > magnitudeY) {
      // user is perhaps trying to swipe left/right across the page

      if (((deltaX < 0) && (scrollLeft === i.contentWidth - i.containerWidth)) ||
          ((deltaX > 0) && (scrollLeft === 0))) {
        return !i.settings.swipePropagation;
      }
    }

    return true;
  }

  function applyTouchMove(differenceX, differenceY) {
    updateScroll(element, 'top', element.scrollTop - differenceY);
    updateScroll(element, 'left', element.scrollLeft - differenceX);

    updateGeometry(element);
  }

  var startOffset = {};
  var startTime = 0;
  var speed = {};
  var easingLoop = null;
  var inGlobalTouch = false;
  var inLocalTouch = false;

  function globalTouchStart() {
    inGlobalTouch = true;
  }
  function globalTouchEnd() {
    inGlobalTouch = false;
  }

  function getTouch(e) {
    if (e.targetTouches) {
      return e.targetTouches[0];
    } else {
      // Maybe IE pointer
      return e;
    }
  }
  function shouldHandle(e) {
    if (e.targetTouches && e.targetTouches.length === 1) {
      return true;
    }
    if (e.pointerType && e.pointerType !== 'mouse' && e.pointerType !== e.MSPOINTER_TYPE_MOUSE) {
      return true;
    }
    return false;
  }
  function touchStart(e) {
    if (shouldHandle(e)) {
      inLocalTouch = true;

      var touch = getTouch(e);

      startOffset.pageX = touch.pageX;
      startOffset.pageY = touch.pageY;

      startTime = (new Date()).getTime();

      if (easingLoop !== null) {
        clearInterval(easingLoop);
      }

      e.stopPropagation();
    }
  }
  function touchMove(e) {
    if (!inGlobalTouch && inLocalTouch && shouldHandle(e)) {
      var touch = getTouch(e);

      var currentOffset = {pageX: touch.pageX, pageY: touch.pageY};

      var differenceX = currentOffset.pageX - startOffset.pageX;
      var differenceY = currentOffset.pageY - startOffset.pageY;

      applyTouchMove(differenceX, differenceY);
      startOffset = currentOffset;

      var currentTime = (new Date()).getTime();

      var timeGap = currentTime - startTime;
      if (timeGap > 0) {
        speed.x = differenceX / timeGap;
        speed.y = differenceY / timeGap;
        startTime = currentTime;
      }

      if (shouldPreventDefault(differenceX, differenceY)) {
        e.stopPropagation();
        e.preventDefault();
      }
    }
  }
  function touchEnd() {
    if (!inGlobalTouch && inLocalTouch) {
      inLocalTouch = false;

      clearInterval(easingLoop);
      easingLoop = setInterval(function () {
        if (!instances.get(element)) {
          clearInterval(easingLoop);
          return;
        }

        if (Math.abs(speed.x) < 0.01 && Math.abs(speed.y) < 0.01) {
          clearInterval(easingLoop);
          return;
        }

        applyTouchMove(speed.x * 30, speed.y * 30);

        speed.x *= 0.8;
        speed.y *= 0.8;
      }, 10);
    }
  }

  if (supportsTouch) {
    i.event.bind(window, 'touchstart', globalTouchStart);
    i.event.bind(window, 'touchend', globalTouchEnd);
    i.event.bind(element, 'touchstart', touchStart);
    i.event.bind(element, 'touchmove', touchMove);
    i.event.bind(element, 'touchend', touchEnd);
  }

  if (supportsIePointer) {
    if (window.PointerEvent) {
      i.event.bind(window, 'pointerdown', globalTouchStart);
      i.event.bind(window, 'pointerup', globalTouchEnd);
      i.event.bind(element, 'pointerdown', touchStart);
      i.event.bind(element, 'pointermove', touchMove);
      i.event.bind(element, 'pointerup', touchEnd);
    } else if (window.MSPointerEvent) {
      i.event.bind(window, 'MSPointerDown', globalTouchStart);
      i.event.bind(window, 'MSPointerUp', globalTouchEnd);
      i.event.bind(element, 'MSPointerDown', touchStart);
      i.event.bind(element, 'MSPointerMove', touchMove);
      i.event.bind(element, 'MSPointerUp', touchEnd);
    }
  }
}

module.exports = function (element, supportsTouch, supportsIePointer) {
  var i = instances.get(element);
  bindTouchHandler(element, i, supportsTouch, supportsIePointer);
};

},{"../instances":220,"../update-geometry":221,"../update-scroll":222}],219:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var cls = require('../lib/class')
  , h = require('../lib/helper')
  , instances = require('./instances')
  , updateGeometry = require('./update-geometry');

// Handlers
var clickRailHandler = require('./handler/click-rail')
  , dragScrollbarHandler = require('./handler/drag-scrollbar')
  , keyboardHandler = require('./handler/keyboard')
  , mouseWheelHandler = require('./handler/mouse-wheel')
  , nativeScrollHandler = require('./handler/native-scroll')
  , selectionHandler = require('./handler/selection')
  , touchHandler = require('./handler/touch');

module.exports = function (element, userSettings) {
  userSettings = typeof userSettings === 'object' ? userSettings : {};

  cls.add(element, 'ps-container');

  // Create a plugin instance.
  var i = instances.add(element);

  i.settings = h.extend(i.settings, userSettings);

  clickRailHandler(element);
  dragScrollbarHandler(element);
  mouseWheelHandler(element);
  nativeScrollHandler(element);

  if (i.settings.useSelectionScroll) {
    selectionHandler(element);
  }

  if (h.env.supportsTouch || h.env.supportsIePointer) {
    touchHandler(element, h.env.supportsTouch, h.env.supportsIePointer);
  }
  if (i.settings.useKeyboard) {
    keyboardHandler(element);
  }

  updateGeometry(element);
};

},{"../lib/class":204,"../lib/helper":208,"./handler/click-rail":212,"./handler/drag-scrollbar":213,"./handler/keyboard":214,"./handler/mouse-wheel":215,"./handler/native-scroll":216,"./handler/selection":217,"./handler/touch":218,"./instances":220,"./update-geometry":221}],220:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var d = require('../lib/dom')
  , defaultSettings = require('./default-setting')
  , EventManager = require('../lib/event-manager')
  , guid = require('../lib/guid')
  , h = require('../lib/helper');

var instances = {};

function Instance(element) {
  var i = this;

  i.settings = h.clone(defaultSettings);
  i.containerWidth = null;
  i.containerHeight = null;
  i.contentWidth = null;
  i.contentHeight = null;

  i.isRtl = d.css(element, 'direction') === "rtl";
  i.isNegativeScroll = (function () {
    var originalScrollLeft = element.scrollLeft;
    var result = null;
    element.scrollLeft = -1;
    result = element.scrollLeft < 0;
    element.scrollLeft = originalScrollLeft;
    return result;
  })();
  i.negativeScrollAdjustment = i.isNegativeScroll ? element.scrollWidth - element.clientWidth : 0;
  i.event = new EventManager();
  i.ownerDocument = element.ownerDocument || document;

  i.scrollbarXRail = d.appendTo(d.e('div', 'ps-scrollbar-x-rail'), element);
  i.scrollbarX = d.appendTo(d.e('div', 'ps-scrollbar-x'), i.scrollbarXRail);
  i.scrollbarX.setAttribute('tabindex', 0);
  i.scrollbarXActive = null;
  i.scrollbarXWidth = null;
  i.scrollbarXLeft = null;
  i.scrollbarXBottom = h.toInt(d.css(i.scrollbarXRail, 'bottom'));
  i.isScrollbarXUsingBottom = i.scrollbarXBottom === i.scrollbarXBottom; // !isNaN
  i.scrollbarXTop = i.isScrollbarXUsingBottom ? null : h.toInt(d.css(i.scrollbarXRail, 'top'));
  i.railBorderXWidth = h.toInt(d.css(i.scrollbarXRail, 'borderLeftWidth')) + h.toInt(d.css(i.scrollbarXRail, 'borderRightWidth'));
  // Set rail to display:block to calculate margins
  d.css(i.scrollbarXRail, 'display', 'block');
  i.railXMarginWidth = h.toInt(d.css(i.scrollbarXRail, 'marginLeft')) + h.toInt(d.css(i.scrollbarXRail, 'marginRight'));
  d.css(i.scrollbarXRail, 'display', '');
  i.railXWidth = null;
  i.railXRatio = null;

  i.scrollbarYRail = d.appendTo(d.e('div', 'ps-scrollbar-y-rail'), element);
  i.scrollbarY = d.appendTo(d.e('div', 'ps-scrollbar-y'), i.scrollbarYRail);
  i.scrollbarY.setAttribute('tabindex', 0);
  i.scrollbarYActive = null;
  i.scrollbarYHeight = null;
  i.scrollbarYTop = null;
  i.scrollbarYRight = h.toInt(d.css(i.scrollbarYRail, 'right'));
  i.isScrollbarYUsingRight = i.scrollbarYRight === i.scrollbarYRight; // !isNaN
  i.scrollbarYLeft = i.isScrollbarYUsingRight ? null : h.toInt(d.css(i.scrollbarYRail, 'left'));
  i.scrollbarYOuterWidth = i.isRtl ? h.outerWidth(i.scrollbarY) : null;
  i.railBorderYWidth = h.toInt(d.css(i.scrollbarYRail, 'borderTopWidth')) + h.toInt(d.css(i.scrollbarYRail, 'borderBottomWidth'));
  d.css(i.scrollbarYRail, 'display', 'block');
  i.railYMarginHeight = h.toInt(d.css(i.scrollbarYRail, 'marginTop')) + h.toInt(d.css(i.scrollbarYRail, 'marginBottom'));
  d.css(i.scrollbarYRail, 'display', '');
  i.railYHeight = null;
  i.railYRatio = null;
}

function getId(element) {
  if (typeof element.dataset === 'undefined') {
    return element.getAttribute('data-ps-id');
  } else {
    return element.dataset.psId;
  }
}

function setId(element, id) {
  if (typeof element.dataset === 'undefined') {
    element.setAttribute('data-ps-id', id);
  } else {
    element.dataset.psId = id;
  }
}

function removeId(element) {
  if (typeof element.dataset === 'undefined') {
    element.removeAttribute('data-ps-id');
  } else {
    delete element.dataset.psId;
  }
}

exports.add = function (element) {
  var newId = guid();
  setId(element, newId);
  instances[newId] = new Instance(element);
  return instances[newId];
};

exports.remove = function (element) {
  delete instances[getId(element)];
  removeId(element);
};

exports.get = function (element) {
  return instances[getId(element)];
};

},{"../lib/dom":205,"../lib/event-manager":206,"../lib/guid":207,"../lib/helper":208,"./default-setting":210}],221:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var cls = require('../lib/class')
  , d = require('../lib/dom')
  , h = require('../lib/helper')
  , instances = require('./instances')
  , updateScroll = require('./update-scroll');

function getThumbSize(i, thumbSize) {
  if (i.settings.minScrollbarLength) {
    thumbSize = Math.max(thumbSize, i.settings.minScrollbarLength);
  }
  if (i.settings.maxScrollbarLength) {
    thumbSize = Math.min(thumbSize, i.settings.maxScrollbarLength);
  }
  return thumbSize;
}

function updateCss(element, i) {
  var xRailOffset = {width: i.railXWidth};
  if (i.isRtl) {
    xRailOffset.left = i.negativeScrollAdjustment + element.scrollLeft + i.containerWidth - i.contentWidth;
  } else {
    xRailOffset.left = element.scrollLeft;
  }
  if (i.isScrollbarXUsingBottom) {
    xRailOffset.bottom = i.scrollbarXBottom - element.scrollTop;
  } else {
    xRailOffset.top = i.scrollbarXTop + element.scrollTop;
  }
  d.css(i.scrollbarXRail, xRailOffset);

  var yRailOffset = {top: element.scrollTop, height: i.railYHeight};
  if (i.isScrollbarYUsingRight) {
    if (i.isRtl) {
      yRailOffset.right = i.contentWidth - (i.negativeScrollAdjustment + element.scrollLeft) - i.scrollbarYRight - i.scrollbarYOuterWidth;
    } else {
      yRailOffset.right = i.scrollbarYRight - element.scrollLeft;
    }
  } else {
    if (i.isRtl) {
      yRailOffset.left = i.negativeScrollAdjustment + element.scrollLeft + i.containerWidth * 2 - i.contentWidth - i.scrollbarYLeft - i.scrollbarYOuterWidth;
    } else {
      yRailOffset.left = i.scrollbarYLeft + element.scrollLeft;
    }
  }
  d.css(i.scrollbarYRail, yRailOffset);

  d.css(i.scrollbarX, {left: i.scrollbarXLeft, width: i.scrollbarXWidth - i.railBorderXWidth});
  d.css(i.scrollbarY, {top: i.scrollbarYTop, height: i.scrollbarYHeight - i.railBorderYWidth});
}

module.exports = function (element) {
  var i = instances.get(element);

  i.containerWidth = element.clientWidth;
  i.containerHeight = element.clientHeight;
  i.contentWidth = element.scrollWidth;
  i.contentHeight = element.scrollHeight;

  var existingRails;
  if (!element.contains(i.scrollbarXRail)) {
    existingRails = d.queryChildren(element, '.ps-scrollbar-x-rail');
    if (existingRails.length > 0) {
      existingRails.forEach(function (rail) {
        d.remove(rail);
      });
    }
    d.appendTo(i.scrollbarXRail, element);
  }
  if (!element.contains(i.scrollbarYRail)) {
    existingRails = d.queryChildren(element, '.ps-scrollbar-y-rail');
    if (existingRails.length > 0) {
      existingRails.forEach(function (rail) {
        d.remove(rail);
      });
    }
    d.appendTo(i.scrollbarYRail, element);
  }

  if (!i.settings.suppressScrollX && i.containerWidth + i.settings.scrollXMarginOffset < i.contentWidth) {
    i.scrollbarXActive = true;
    i.railXWidth = i.containerWidth - i.railXMarginWidth;
    i.railXRatio = i.containerWidth / i.railXWidth;
    i.scrollbarXWidth = getThumbSize(i, h.toInt(i.railXWidth * i.containerWidth / i.contentWidth));
    i.scrollbarXLeft = h.toInt((i.negativeScrollAdjustment + element.scrollLeft) * (i.railXWidth - i.scrollbarXWidth) / (i.contentWidth - i.containerWidth));
  } else {
    i.scrollbarXActive = false;
  }

  if (!i.settings.suppressScrollY && i.containerHeight + i.settings.scrollYMarginOffset < i.contentHeight) {
    i.scrollbarYActive = true;
    i.railYHeight = i.containerHeight - i.railYMarginHeight;
    i.railYRatio = i.containerHeight / i.railYHeight;
    i.scrollbarYHeight = getThumbSize(i, h.toInt(i.railYHeight * i.containerHeight / i.contentHeight));
    i.scrollbarYTop = h.toInt(element.scrollTop * (i.railYHeight - i.scrollbarYHeight) / (i.contentHeight - i.containerHeight));
  } else {
    i.scrollbarYActive = false;
  }

  if (i.scrollbarXLeft >= i.railXWidth - i.scrollbarXWidth) {
    i.scrollbarXLeft = i.railXWidth - i.scrollbarXWidth;
  }
  if (i.scrollbarYTop >= i.railYHeight - i.scrollbarYHeight) {
    i.scrollbarYTop = i.railYHeight - i.scrollbarYHeight;
  }

  updateCss(element, i);

  if (i.scrollbarXActive) {
    cls.add(element, 'ps-active-x');
  } else {
    cls.remove(element, 'ps-active-x');
    i.scrollbarXWidth = 0;
    i.scrollbarXLeft = 0;
    updateScroll(element, 'left', 0);
  }
  if (i.scrollbarYActive) {
    cls.add(element, 'ps-active-y');
  } else {
    cls.remove(element, 'ps-active-y');
    i.scrollbarYHeight = 0;
    i.scrollbarYTop = 0;
    updateScroll(element, 'top', 0);
  }
};

},{"../lib/class":204,"../lib/dom":205,"../lib/helper":208,"./instances":220,"./update-scroll":222}],222:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var instances = require('./instances');

var upEvent = document.createEvent('Event')
  , downEvent = document.createEvent('Event')
  , leftEvent = document.createEvent('Event')
  , rightEvent = document.createEvent('Event')
  , yEvent = document.createEvent('Event')
  , xEvent = document.createEvent('Event')
  , xStartEvent = document.createEvent('Event')
  , xEndEvent = document.createEvent('Event')
  , yStartEvent = document.createEvent('Event')
  , yEndEvent = document.createEvent('Event')
  , lastTop
  , lastLeft;

upEvent.initEvent('ps-scroll-up', true, true);
downEvent.initEvent('ps-scroll-down', true, true);
leftEvent.initEvent('ps-scroll-left', true, true);
rightEvent.initEvent('ps-scroll-right', true, true);
yEvent.initEvent('ps-scroll-y', true, true);
xEvent.initEvent('ps-scroll-x', true, true);
xStartEvent.initEvent('ps-x-reach-start', true, true);
xEndEvent.initEvent('ps-x-reach-end', true, true);
yStartEvent.initEvent('ps-y-reach-start', true, true);
yEndEvent.initEvent('ps-y-reach-end', true, true);

module.exports = function (element, axis, value) {
  if (typeof element === 'undefined') {
    throw 'You must provide an element to the update-scroll function';
  }

  if (typeof axis === 'undefined') {
    throw 'You must provide an axis to the update-scroll function';
  }

  if (typeof value === 'undefined') {
    throw 'You must provide a value to the update-scroll function';
  }

  if (axis === 'top' && value <= 0) {
    element.scrollTop = 0;
    element.dispatchEvent(yStartEvent);
    return; // don't allow negative scroll
  }

  if (axis === 'left' && value <= 0) {
    element.scrollLeft = 0;
    element.dispatchEvent(xStartEvent);
    return; // don't allow negative scroll
  }

  var i = instances.get(element);

  if (axis === 'top' && value >= i.contentHeight - i.containerHeight) {
    element.scrollTop = i.contentHeight - i.containerHeight;
    element.dispatchEvent(yEndEvent);
    return; // don't allow scroll past container
  }

  if (axis === 'left' && value >= i.contentWidth - i.containerWidth) {
    element.scrollLeft = i.contentWidth - i.containerWidth;
    element.dispatchEvent(xEndEvent);
    return; // don't allow scroll past container
  }

  if (!lastTop) {
    lastTop = element.scrollTop;
  }

  if (!lastLeft) {
    lastLeft = element.scrollLeft;
  }

  if (axis === 'top' && value < lastTop) {
    element.dispatchEvent(upEvent);
  }

  if (axis === 'top' && value > lastTop) {
    element.dispatchEvent(downEvent);
  }

  if (axis === 'left' && value < lastLeft) {
    element.dispatchEvent(leftEvent);
  }

  if (axis === 'left' && value > lastLeft) {
    element.dispatchEvent(rightEvent);
  }

  if (axis === 'top') {
    element.scrollTop = lastTop = value;
    element.dispatchEvent(yEvent);
  }

  if (axis === 'left') {
    element.scrollLeft = lastLeft = value;
    element.dispatchEvent(xEvent);
  }

};

},{"./instances":220}],223:[function(require,module,exports){
/* Copyright (c) 2015 Hyunje Alex Jun and other contributors
 * Licensed under the MIT License
 */
'use strict';

var d = require('../lib/dom')
  , h = require('../lib/helper')
  , instances = require('./instances')
  , updateGeometry = require('./update-geometry')
  , updateScroll = require('./update-scroll');

module.exports = function (element) {
  var i = instances.get(element);

  if (!i) {
    return;
  }

  // Recalcuate negative scrollLeft adjustment
  i.negativeScrollAdjustment = i.isNegativeScroll ? element.scrollWidth - element.clientWidth : 0;

  // Recalculate rail margins
  d.css(i.scrollbarXRail, 'display', 'block');
  d.css(i.scrollbarYRail, 'display', 'block');
  i.railXMarginWidth = h.toInt(d.css(i.scrollbarXRail, 'marginLeft')) + h.toInt(d.css(i.scrollbarXRail, 'marginRight'));
  i.railYMarginHeight = h.toInt(d.css(i.scrollbarYRail, 'marginTop')) + h.toInt(d.css(i.scrollbarYRail, 'marginBottom'));

  // Hide scrollbars not to affect scrollWidth and scrollHeight
  d.css(i.scrollbarXRail, 'display', 'none');
  d.css(i.scrollbarYRail, 'display', 'none');

  updateGeometry(element);

  // Update top/left scroll to trigger events
  updateScroll(element, 'top', element.scrollTop);
  updateScroll(element, 'left', element.scrollLeft);

  d.css(i.scrollbarXRail, 'display', '');
  d.css(i.scrollbarYRail, 'display', '');
};

},{"../lib/dom":205,"../lib/helper":208,"./instances":220,"./update-geometry":221,"./update-scroll":222}]},{},[4])(4)
});
//# sourceMappingURL=builder.map
