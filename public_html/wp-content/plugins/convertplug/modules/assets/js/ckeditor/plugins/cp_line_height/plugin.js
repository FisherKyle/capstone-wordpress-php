/**
 * CKEditor plugin: ConvertPlug
 *
 */
( function() {
  function addCombo( editor, comboName, styleType, lang, entries, defaultLabel, styleDefinition, order ) {
    var config = editor.config,style = new CKEDITOR.style( styleDefinition );   
    var names = entries.split( ';' ),values = [];   
    var styles = {};
    for ( var i = 0; i < names.length; i++ ) {
      var parts = names[ i ];
      if ( parts ) {
        parts = parts.split( '/' );
        var vars = {},name = names[ i ] = parts[ 0 ];
        vars[ styleType ] = values[ i ] = parts[ 1 ] || name;
        styles[ name ] = new CKEDITOR.style( styleDefinition, vars );
        styles[ name ]._.definition.name = name;
      } else
        names.splice( i--, 1 );
    }
    editor.ui.addRichCombo( comboName, {
      label: 'Line Height', //editor.lang.tokens.title,
      title: 'Line Height', //editor.lang.tokens.title,
      toolbar: 'styles,' + order,
      icons: 'okkkkkkkkkk',
      allowedContent: style,
      requiredContent: style,
      panel: {
        css: [ CKEDITOR.skin.getPath( 'editor' ) ].concat( config.contentsCss ),
        multiSelect: false,
        attributes: { 'aria-label': 'Line Height' /*editor.lang.tokens.title*/ }
      },
      init: function() {
        this.startGroup(/*editor.lang.tokens.title*/ 'Line Height' );
        for ( var i = 0; i < names.length; i++ ) {
          var name = names[ i ];          
          this.add( name, styles[ name ].buildPreview(), name );
        }
      },
      onClick: function( value ) {
        editor.focus();
        editor.fire( 'saveSnapshot' );
        var style = styles[ value ];
        editor[ this.getValue() == value ? 'removeStyle' : 'applyStyle' ]( style );
        editor.fire( 'saveSnapshot' );
      },
      onRender: function() {
        editor.on( 'selectionChange', function( ev ) {
          var currentValue = this.getValue();
          var elementPath = ev.data.path,elements = elementPath.elements;
          for ( var i = 0, element; i < elements.length; i++ ) {
            element = elements[ i ];
            for ( var value in styles ) {
              if ( styles[ value ].checkElementMatch( element, true, editor ) ) {
                if ( value != currentValue )
                  this.setValue( value );
                return;
              }
            }
          }
          this.setValue( '', defaultLabel );
        }, this );
      },
      refresh: function() {
        if ( !editor.activeFilter.check( style ) )
          this.setState( CKEDITOR.TRISTATE_DISABLED );
      }
    } );
  }
  CKEDITOR.plugins.add( 'cp_line_height', {
    requires: 'richcombo',
    //lang: 'en,fr,es',
    init: function( editor ) {
      var config = editor.config;
      addCombo( editor, 'cp_line_height', 'size', 'Line Height'/*editor.lang.tokens.title*/, config.line_height, 'Line Height'/*editor.lang.tokens.title*/, config.cp_line_height_style, 40 );
    }
  } );
} )();
//CKEDITOR.config.line_height = '0.1/0.1em;0.2/0.2em;0.3/0.3em;0.4/0.4em;0.5/0.5em;0.6/0.6em;0.7/0.7em;0.8/0.8em;0.9/0.9em;1/1em;1.1/1.1em;1.2/1.2em;1.3/1.3em;1.4/1.4em;1.5/1.5em;1.6/1.6em;1.7/1.7em;1.8/1.8em;1.9/1.9em;2/2em;2.1/2.1em;2.2/2.2em;2.3/2.3em;2.4/2.4em;2.5/2.5em;2.6/2.6em;2.7/2.7em;2.8/2.8em;2.9/2.9em;3/3em;3.1/3.1em;3.2/3.2em;3.3/3.3em;3.4/3.4em;3.5/3.5em;3.6/3.6em;3.7/3.7em;3.8/3.8em;3.9/3.9em;4/4em;4.1/4.1em;4.2/4.2em;4.3/4.3em;4.4/4.4em;4.5/4.5em;4.6/4.6em;4.7/4.7em;4.8/4.8em;4.9/4.9em;5/5em;5.1/5.1em;5.2/5.2em;5.3/5.3em;5.4/5.4em;5.5/5.5em;5.6/5.6em;5.7/5.7em;5.8/5.8em;5.9/5.9em;6/6em;6.1/6.1em;6.2/6.2em;6.3/6.3em;6.4/6.4em;6.5/6.5em;6.6/6.6em;6.7/6.7em;6.8/6.8em;6.9/6.9em;7/7em;7.1/7.1em;7.2/7.2em;7.3/7.3em;7.4/7.4em;7.5/7.5em;7.6/7.6em;7.7/7.7em;7.8/7.8em;7.9/7.9em;8/8em;8.1/8.1em;8.2/8.2em;8.3/8.3em;8.4/8.4em;8.5/8.5em;8.6/8.6em;8.7/8.7em;8.8/8.8em;8.9/8.9em;9/9em;9.1/9.1em;9.2/9.2em;9.3/9.3em;9.4/9.4em;9.5/9.5em;9.6/9.6em;9.7/9.7em;9.8/9.8em;9.9/9.9em;10/10em;';
CKEDITOR.config.line_height = '8/8px;9/9px;10/10px;11/11px;12/12px;13/13px;14/14px;15/15px;16/16px;17/17px;18/18px;19/19px;20/20px;21/21px;22/22px;23/23px;24/24px;25/25px;26/26px;27/27px;28/28px;29/29px;30/30px;31/31px;32/32px;33/33px;34/34px;35/35px;36/36px;37/37px;38/38px;39/39px;40/40px;41/41px;42/42px;43/43px;44/44px;45/45px;46/46px;47/47px;48/48px;49/49px;50/50px;51/51px;52/52px;53/53px;54/54px;55/55px;56/56px;57/57px;58/58px;59/59px;60/60px;61/61px;62/62px;63/63px;64/64px;65/65px;66/66px;67/67px;68/68px;69/69px;70/70px;71/71px;72/72px;73/73px;74/74px;75/75px;76/76px;77/77px;78/78px;79/79px;80/80px;81/81px;82/82px;83/83px;84/84px;85/85px;86/86px;87/87px;88/88px;89/89px;90/90px;91/91px;92/92px;93/93px;94/94px;95/95px;96/96px;97/97px;98/98px;99/99px;100/100px;';
CKEDITOR.config.cp_line_height_style = {
  element: 'span',
  styles: { 'line-height': '#(size)' },
  overrides: [ {
    element: 'line-height', attributes: { 'size': null, }
  } ]
};