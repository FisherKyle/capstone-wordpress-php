/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here. For example:
    config.language = 'en';
    config.contentsLangDirection = 'ltr';
    // config.uiColor = '#AADC6E';
    // Modify the toolbar groups.
    config.toolbarGroups = [
        { name: 'basicstyles', groups: [ 'basicstyles', 'list' ] },
        { name: 'paragraph',   groups: [ 'list', 'align' ] },
        { name: 'colors' },
        { name: 'styles' },
        { name: 'links' },
        { name: 'insert', groups: ['Image', 'Youtube'] },
    ];

    // Override core styles for bold, italic and underlined to allow styling defaults.
    config.coreStyles_bold      = { element : 'span', attributes : { 'style' : 'font-weight:bold' } };
    config.coreStyles_italic    = { element : 'span', attributes : { 'style' : 'font-style:italic' } };
    config.coreStyles_underline = { element : 'span', attributes : { 'style' : 'text-decoration:underline' } };

    // This is actually the default value for it.
    config.fontSize_style       = { element : 'span', styles: { 'font-size': '#(size)' }, attributes : { 'data-font-size': '#(size)', 'class' : 'cp_responsive cp_font' } };
    config.cp_line_height_style = { element : 'span', styles: { 'line-height': '#(size)' }, attributes : { 'data-line-height': '#(size)', 'class' : 'cp_responsive cp_line_height' } };
    // config.justifyClasses = [ 'CPAlignLeft', 'CPAlignCenter', 'CPAlignRight', 'CPAlignJustify' ];

    // config.fontSize_style = {
    //     element:        'span',
    //     styles:         { 'font-size': '#(size)' },
    //     overrides:      [ { element :'font', attributes: { 'size': null, 'class' : 'okkkkkk' } } ]
    // };

    // Handle other config properties.
    config.removeButtons            =   'Strike,Subscript,Superscript,Styles,Flash,SpecialChar,PageBreak,Iframe,Smiley';
    config.removePlugins            =   'iframe';
    config.format_tags              =   'p;h1;h2;h3;pre';
    //config.removeDialogTabs       =   'image:advanced;link:advanced';
    config.removeDialogTabs         =   'image:advanced;';
    config.baseFloatZIndex          =   6351541435;
    config.enterMode                =   CKEDITOR.ENTER_BR;
    config.shiftEnterMode           =   CKEDITOR.ENTER_BR;
    config.allowedContent           =   true;
    config.extraAllowedContent      =   'div(*)';
    //config.extraPlugins           =   'youtube,pastetext,youtube,convertplug'; //   Other s- imageresize, dragresize
    config.extraPlugins             =   'sourcedialog,cp_line_height'; //   Other s- imageresize, dragresize

    //  Remove Magic Line
    // config.magicline_everywhere = false;
    config.removePlugins = 'magicline';

    config.forcePasteAsPlainText = true;

    // allow empty span tags
    config.protectedSource.push(/<span[^>]*><\/span>/g);

    /** = ConvertPlug - Fonts Sizes - [ 8-100 px]
     *-----------------------------------------------------------*/
    config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;13/13px;14/14px;15/15px;16/16px;17/17px;18/18px;19/19px;20/20px;21/21px;22/22px;23/23px;24/24px;25/25px;26/26px;27/27px;28/28px;29/29px;30/30px;31/31px;32/32px;33/33px;34/34px;35/35px;36/36px;37/37px;38/38px;39/39px;40/40px;41/41px;42/42px;43/43px;44/44px;45/45px;46/46px;47/47px;48/48px;49/49px;50/50px;51/51px;52/52px;53/53px;54/54px;55/55px;56/56px;57/57px;58/58px;59/59px;60/60px;61/61px;62/62px;63/63px;64/64px;65/65px;66/66px;67/67px;68/68px;69/69px;70/70px;71/71px;72/72px;73/73px;74/74px;75/75px;76/76px;77/77px;78/78px;79/79px;80/80px;81/81px;82/82px;83/83px;84/84px;85/85px;86/86px;87/87px;88/88px;89/89px;90/90px;91/91px;92/92px;93/93px;94/94px;95/95px;96/96px;97/97px;98/98px;99/99px;100/100px;';

    /** = Toolbar
     *-----------------------------------------------------------*/
    // 1. Create
    config.toolbar = 'cp_toolbar';
    config.toolbar_cp_toolbar =
    [
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat', '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
        { name: 'paragraph', items : [ '-', 'NumberedList','BulletedList', ] },
        { name: 'links', items : [ '-', 'Link','Unlink', ] },
        { name: 'colors', items : [ '-', 'TextColor','BGColor' ] },
        { name: 'styles', items : [ '-', 'Styles', 'Font','FontSize' ] },
        [ '-', 'cp_line_height' ],
        { name: 'document', items: [ 'Sourcedialog' ] },
        //[ 'cp' ],
    ];

    // Problem with apostrophe ( ' ) symbol
    // https://www.drupal.org/node/803562#comment-2988196
    config.htmlEncodeOutput = true;
    config.entities = false;
    config.fillEmptyBlocks = false;

    /** = Skin
     *-----------------------------------------------------------*/
    config.skin = 'minimalist';
    // config.skin = 'icy_orange';


    /** = Position
     *-----------------------------------------------------------*/
    //config.toolbarLocation = 'bottom';

    //gFonts = ['Aclonica', 'Allan', 'Allerta', 'Allerta Stencil', 'Amaranth', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Anton', 'Architects Daughter', 'Arimo', 'Artifika', 'Arvo', 'Astloch', 'Bangers', 'Battambang', 'Bayon', 'Bentham', 'Bevan', 'Bigshot One', 'Bokor', 'Brawler', /*'Buda',*/ 'Cabin', 'Cabin Sketch', 'Calligraffitti', 'Candal', 'Cantarell', 'Cardo', 'Carter One', 'Caudex', 'Chenla', 'Cherry Cream Soda', 'Chewy', 'Coda', /*'Coda Caption',*/ 'Coming Soon', 'Content', 'Copse', 'Corben', 'Cousine', 'Covered By Your Grace', 'Crafty Girls', 'Crimson Text', 'Crushed', 'Cuprum', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Didact Gothic', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'EB Garamond', 'Expletus Sans', 'Fontdiner Swanky', 'Francois One', 'Freehand', 'GFS Didot', 'GFS Neohellenic', 'Geo', 'Goudy Bookletter 1911', 'Gruppo', 'Hanuman', 'Holtwood One SC', 'Homemade Apple', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Inconsolata', 'Indie Flower', 'Irish Grover', 'Josefin Sans', 'Josefin Slab', 'Judson', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kenia', 'Khmer', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Lato', 'League Script', 'Lekton', 'Limelight', 'Lobster', 'Lora', 'Luckiest Guy', 'Maiden Orange', 'Mako', 'Maven Pro', 'Meddon', 'MedievalSharp', 'Megrim', 'Merriweather', 'Metal', 'Metrophobic', 'Michroma', 'Miltonian', 'Miltonian Tattoo', 'Molengo', 'Monofett', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Muli', 'Neucha', 'Neuton', 'News Cycle', 'Nobile', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Old Standard TT', 'Open Sans', /*'Open Sans Condensed',*/ 'Orbitron', 'Oswald', 'Over the Rainbow', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paytone One', 'Permanent Marker', 'Philosopher', 'Play', 'Playfair Display', 'Podkova', 'Preahvihear', 'Puritan', 'Quattrocento', 'Quattrocento Sans', 'Radley', 'Raleway', 'Reenie Beanie', 'Rock Salt', 'Rokkitt', 'Ruslan Display', 'Schoolbell', 'Shanti', 'Siemreap', 'Sigmar One', 'Six Caps', 'Slackey', 'Smythe', 'Sniglet', 'Special Elite', 'Sue Ellen Francisco', 'Sunshiney', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Taprom', 'Tenor Sans', 'Terminal Dosis Light', 'The Girl Next Door', 'Tinos', 'Ubuntu', 'Ultra', /*'UnifrakturCook',*/ 'UnifrakturMaguntia', 'Unkempt', 'VT323', 'Vibur', 'Vollkorn', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Wire One', 'Yanone Kaffeesatz'];
    //config.font_names = 'serif;sans serif;monospace;cursive;fantasy';
    //for(var i = 0; i<gFonts.length; i++){
    //    config.font_names = config.font_names+';'+gFonts[i];
    //    gFonts[i] = 'https://fonts.googleapis.com/css?family='+gFonts[i].replace(' ','+');
    //}
   //config.contentsCss = ['/imedica-demos/wp-content/plugins/convertplug/modules/modal/assets/js/ckeditor/contents.css'].concat(gFonts);

};
