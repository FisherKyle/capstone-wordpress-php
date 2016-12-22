(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.CS_generator2 = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = {

	'main': require('./main'),
	'library': require('./library'),

}
},{"./library":2,"./main":3}],2:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( ) {
		cs.elementLibrary.registerContext( 'generator', this.elementFilter );
	},

	elementFilter: function( child ) {

  	// Hide inactive or out-of-context
		var flags = child.get('flags');
		if ( child.get('active') == false || !_.contains( ['generator','all'], flags.context )  )
			return false;

  	return true;
  },

});
},{}],3:[function(require,module,exports){
module.exports = Cornerstone.Component.extend({

	initialize: function( options ) {

		cs.generator = Backbone.Radio.channel( 'cs:generator' );
		cs.loadTemplates( require('../../tmp/templates-generator2.js' ) );

		Backbone.$(document).on( 'click', '#cs-insert-shortcode-button', _.bind( function( e ) {
      e.preventDefault();
      cs.generator.trigger( 'open' );
    }, this ) );

		this.$root = Backbone.$( "#cornerstone-generator" );

		if ( this.$root.length < 1 ) {
			this.$root = Backbone.$('<div id="cornerstone-generator"></div>');
			Backbone.$('body').append( this.$root );
		}

		var MainView = require('../views/main');
    this.view = new MainView({ el: '#cornerstone-generator' });

	}

});
},{"../../tmp/templates-generator2.js":10,"../views/main":8}],4:[function(require,module,exports){
cs.registerComponents( require('./components') );
cs.updateConfig( csGeneratorData );
cs.updateRegistry( {
	start: [ 'main', 'library' ]
} );
},{"./components":1}],5:[function(require,module,exports){
module.exports = CS.Mn.LayoutView.extend({

	className: 'csg-modal-main',
	template: 'generator/content',

  // regions: {
  //   Library: '#csg-modal-sidebar',
  // },

	initialize: function() {

	},

	onBeforeShow: function() {

		//this.Library.show( new ViewSidebar() );

	},

});
},{}],6:[function(require,module,exports){
module.exports = CS.Mn.ItemView.extend({
  tagName: "li",
  className: "csg-library-item",
  template: 'generator/library/item',

  events: {
  	'click': 'click'
  },

  initialize: function() {
  	this.listenTo( cs.generator, 'selected', this.toggleSelection );
  },

  serializeData: function() {
		return _.extend( CS.Mn.ItemView.prototype.serializeData.apply(this,arguments), {
			icon: cs.icon( this.model.get( 'icon' ) )
		});
	},

	toggleSelection: function( name ) {
		this.$el.toggleClass('active', ( name == this.model.get( 'name' ) ) );
	},

	click: function() {
		cs.generator.trigger( 'selected', this.model.get( 'name' ) );
	}

});
},{}],7:[function(require,module,exports){
module.exports = CS.Mn.CollectionView.extend({
	tagName: 'ul',
	className: 'csg-library',
	childView: require('./item'),

	initialize: function( ) {
		this.listenTo( cs.generator, 'search:updated', this.render );
	},

	onBeforeRender: function() {
		this.searchResults = cs.generator.request( 'search:results' ) || '';
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
  },

});
},{"./item":6}],8:[function(require,module,exports){
var ViewSidebar = require('./sidebar.js');
var ViewContent = require('./content.js');

module.exports = CS.Mn.LayoutView.extend({

	template: 'generator/main',

	events: {
    "click .csg-modal-close" : "closeButton" ,
    "click #btn-ok"          : "insertShortcode" ,
  },

  regions: {
    Sidebar: '#csg-modal-sidebar',
    Content: '#csg-modal-content',
  },

	initialize: function() {
		this.listenTo( cs.generator, 'open', this.render );
		this.listenTo( cs.generator, 'close', this.close );
	},

	closeButton: function() {
		cs.generator.trigger( 'close' );
	},

	insertShortcode: function() {
		cs.generator.trigger( 'insert:shortcode' );
		cs.generator.trigger( 'close' );
	},

	onRender: function() {

		this.Sidebar.show( new ViewSidebar() );
    this.Content.show( new ViewContent() );

    this.$('#csg-search').focus();
	},

	close: function() {
		this.Sidebar.empty();
		this.Content.empty();
		this.unbind();
		this.$el.empty();
	},

});
},{"./content.js":5,"./sidebar.js":9}],9:[function(require,module,exports){
var ViewLibrary = require('./library/list.js');
module.exports = CS.Mn.LayoutView.extend({

	className: 'csg-modal-sidebar',
	template: 'generator/sidebar',

	events: {
		'keyup #csg-search': 'search',
    'search #csg-search': 'search'
	},

  regions: {
    Library: '#csg-modal-library',
  },

	search: function() {
		var results = cs.elementLibrary.search( 'generator', this.$('#csg-search').val().toLowerCase().trim() );
    cs.generator.reply( 'search:results', results );
    cs.generator.trigger( 'search:updated' );
  },

	onBeforeShow: function() {
		var collection = cs.elementLibrary.all()
		this.Library.show( new ViewLibrary({ collection: cs.elementLibrary.get( 'generator' ) }) );

	},

});
},{"./library/list.js":7}],10:[function(require,module,exports){
var templates={};templates['generator/content']=function (obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '';

}
return __p
};templates['generator/main']=function (obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<div class="csg-modal">\n  <header>\n    <h1>' +
((__t = ( l18n('modal-title') )) == null ? '' : __t) +
'</h1>\n    <a class="csg-modal-btn csg-modal-close" href="#">\n      <span class="dashicons dashicons-no"></span>\n    </a>\n  </header>\n  <div class="csg-modal-content">\n    <section id="csg-modal-sidebar"></section>\n    <section id="csg-modal-content"></section>\n  </div>\n  <footer>\n    <button id="btn-ok" disabled class="button button-primary button-large">' +
((__t = ( l18n('modal-insert-shortcode') )) == null ? '' : __t) +
'</button>\n  </footer>\n</div>\n<div class="csg-modal-backdrop">&nbsp;</div>';

}
return __p
};templates['generator/sidebar']=function (obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<div class="csg-search-container">\n  <div class="csg-search">\n    <input type="search" placeholder="' +
((__t = ( l18n('elements-search') )) == null ? '' : __t) +
'" id="csg-search">\n    <i class="cs-icon" data-cs-icon="' +
((__t = ( fontIcon('search') )) == null ? '' : __t) +
'"></i>\n  </div>\n</div>\n<div id="csg-modal-library"></div>';

}
return __p
};templates['generator/library/item']=function (obj) {
obj || (obj = {});
var __t, __p = '';
with (obj) {
__p += '<span class="name">' +
((__t = ( ui.title )) == null ? '' : __t) +
'</span>';

}
return __p
};module.exports=templates;
},{}]},{},[4])(4)
});
//# sourceMappingURL=generator2.map

/* Modules bundled with Browserify */
