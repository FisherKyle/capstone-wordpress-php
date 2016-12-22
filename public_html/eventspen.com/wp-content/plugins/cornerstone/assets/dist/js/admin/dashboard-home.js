(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.CS_dashboardHome = f()}})(function(){var define,module,exports;return (function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
tco.addDataSource( csDashboardHomeData );
tco.addModule( 'cs-updates', require( './home/auto-updates' ) );
tco.addModule( 'cs-validation', require( './home/validation' ) );
tco.addModule( 'cs-validation-revoke', require( './home/revoke' ) );

//
// Notices
//

( function() {

  if ( ! csDashboardHomeData.modules || ! csDashboardHomeData.notices ) return;

  for ( var moduleName in csDashboardHomeData.modules ) {

    var module = csDashboardHomeData.modules[moduleName];

    if ( module.notices ) {

      for ( var noticeKey in module.notices ) {

        var notice = module.notices[noticeKey];

        if ( -1 !== csDashboardHomeData.notices.indexOf( noticeKey ) ) {
          tco.showNotice( module.notices[noticeKey] );
        }

      }

    }

  }

} )();

},{"./home/auto-updates":2,"./home/revoke":3,"./home/validation":4}],2:[function(require,module,exports){
module.exports = function( $this, targets, data ) {

	var $checkNow    = targets['check-now'] || false,
  $latestAvailable = targets['latest-available'] || false;

  if ( ! $checkNow || ! $latestAvailable ) return;

  if ( data.latest ) {
    $latestAvailable.html( data.latest );
  }

  $checkNow.find( 'a' ).click( function( e ) {

    e.preventDefault();

    $checkNow.html( data.checking );

    tco.ajax({

      action: 'cs_update_check',
      _cs_nonce: csDashboardHomeData._cs_nonce,

      done: function( response ) {

        if ( response.latest && response.latest !== data.latest ) {
          $checkNow.html( data.completeNew );
          $latestAvailable.html( response.latest );
        } else {
          $checkNow.html( data.complete );
        }

      },

      fail: function( response ) {
        console.warn( 'Cornerstone Update Check Error', response );
        $checkNow.html( data.error );
      }

    });

  });

};

},{}],3:[function(require,module,exports){
module.exports = function( $this, targets, data ) {

  var $revoke = targets.revoke || false;

  if ( ! $revoke ) return;

  $revoke.click( function() {

    tco.confirm( {
      message: data.confirm,
      acceptClass: 'tco-btn-nope',
      acceptBtn: data.accept,
      declineBtn: data.decline,
      accept: function() {
        $revoke.removeAttr( 'href' );
        $revoke.html( data.revoking );
        tco.ajax({ action: 'cs_validation_revoke', done: reload, fail: reload, _cs_nonce: csDashboardHomeData._cs_nonce } );
      }
    } );

  });

  function reload() {
    var args = tco.queryString.parse( tco.queryString.extract( window.location.href ) );
    delete args['tco-key'];
    args.notice = 'validation-revoked';
    window.location.search = tco.queryString.stringify( args );
  }

};

},{}],4:[function(require,module,exports){
module.exports = function( $this, targets, data ) {

	var $message = targets.message || false,
	$button      = targets.button || false,
	$overlay     = targets.overlay || false,
	$input       = targets.input || false,
	$form        = targets.form || false;
	$preloadKey  = targets['preload-key'] || false;

	if ( ! $message || ! $button || ! $overlay || ! $input || ! $form || ! $preloadKey ) return;

	$form.on( 'submit', function( e ) {

		e.preventDefault();

		$input.prop( 'disabled', true );
		$this.tcoShowMessage( data.verifying );

		tco.ajax({
			action: 'cs_validation',
			code: $input.val(),
			_cs_nonce: csDashboardHomeData._cs_nonce,
			done: done,
			fail: fail
		});

	} );

	var preloadKey = $preloadKey.val();
	if ( 'string' === typeof preloadKey && preloadKey.length > 1 ) {
		$input.val( preloadKey );
		$form.submit();
	}

	function done( response ) {

		if ( ! response.message ) {
			return fail( response );
		}

		if ( response.complete ) {
			$this.tcoShowMessage( response.message );
			setTimeout( complete, 2500 );
		} else {
			incomplete( response );
		}

	}

	function incomplete( response ) {

		$message.html( response.message );
		$button.html( response.button );

		var baseDelay = 650;
		setTimeout( function() {
			$this.tcoShowMessage( '' );
		}, baseDelay * 2 );

		setTimeout( function() {
			$overlay.addClass( 'tco-active' );
		}, baseDelay * 3 );

		if ( response.url ) {
			$button.attr( 'href', response.url );
			if ( response.newTab ) {
				$button.attr( 'target', '_blank' );
			}
		} else {
			$button.attr( 'href', '#' );
		}

		$button.off( 'click' );
		if ( response.dismiss ) {
			$button.click( function() {
				$overlay.removeClass( 'tco-active' );
				$this.tcoRemoveMessage();
				setTimeout( function() {
					$input.val( '' ).prop( 'disabled', false );
				}, baseDelay * 2 );

			} );
		}

	}

	function complete() {
		var args = tco.queryString.parse( window.location.search );
		delete args['tco-key'];
		args.notice = 'validation-complete';
		window.location.search = tco.queryString.stringify( args );
	}

	function fail( response ) {

		var message = ( response.message ) ? response.message : response;

		if ( message.responseText ) {
			message = message.responseText;
		}

		incomplete( {
			message: data.error,
			button:  data.errorButton,
			dismiss: true
		} );

		$message.find( '[data-tco-error-details]' ).click( function( e ) {
			e.preventDefault();
			tco.confirm( {
				message: message,
				acceptBtn: '',
				declineBtn: data.errorButton,
				class: 'tco-confirm-error'
			});
		} );

		console.log( response );

	}

	jQuery( 'body' ).on( 'click', 'a[data-tco-focus="validation-input"]', function( e ) {
		e.preventDefault();
		$input.focus();
	});

};

},{}]},{},[1])(1)
});
//# sourceMappingURL=dashboard-home.map
