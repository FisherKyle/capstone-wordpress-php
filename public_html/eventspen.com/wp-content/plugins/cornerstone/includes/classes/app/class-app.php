<?php

class Cornerstone_App extends Cornerstone_Plugin_Component {

  public $initial_route = false;
  public $show_admin_bar = false;

  public function setup() {
    add_action( 'parse_request', array( $this, 'detect_load' ) );
    if ( $this->is_preview() ) {
      add_filter( 'show_admin_bar', '__return_false' );
    }
  }

  public function detect_load( $wp ) {

    if ( ! apply_filters( '_cornerstone_alpha', false ) ) {
      return;
    }

    // Check if we're loading the ugly way
    $ugly = ( isset( $_GET['cs_app'] ) && '1' === $_GET['cs_app'] );

    // Or if we're loading the nice way
    $nice = false;
    if ( $wp->request ) {

      // If we have a request, see if it matches our app slug
      $parts = explode( '/', $wp->request );

      if ( is_array( $parts ) && $parts[0] === $this->plugin->common()->get_app_slug() ) {

        if ( 1 === count( $parts[0] ) && '/' !== substr( $_SERVER['REQUEST_URI'], -1, 1 ) ) {
          wp_safe_redirect( $wp->request . '/' );
        }

        $nice = true;
      }

    }

    // Bail if we're not loading
    if ( !$ugly && !$nice ) {
      return;
    }

    $can_redirect = ( $ugly && !$nice && $this->plugin->component( 'Router' )->is_permalink_structure_valid() );

    // Allow an initial route to be passed if not using permalinks
    if ( isset( $_GET['cs_route'] ) ) {

      $route = esc_attr( base64_decode( $_GET['cs_route'], true ) );

      if ( $route ) {

        // If we loaded ugly but we can use nice URLs, let's redirect.
        if ( $can_redirect ) {

          $redirect = add_query_arg( array(
            'cs_route' => esc_attr( $_GET['cs_route'] )
          ), trailingslashit( home_url( $this->plugin->common()->get_app_slug() ) ) );

          wp_safe_redirect( $redirect );
          exit;
        }

        $this->initial_route = $route;

      }

    } elseif ( $can_redirect ) {
      // redirect /?cs_app=1 to nice URL if supported
      wp_safe_redirect( trailingslashit( home_url( $this->plugin->common()->get_app_slug() ) ) );
      exit;
    }

    // Onwards
    $this->load();

  }

  public function load() {

    do_action( 'cornerstone_boot_app' );

    $settings = $this->plugin->settings();

    add_filter( 'template_include', '__return_empty_string', 999999 );

    remove_all_actions( 'wp_enqueue_scripts' );
    remove_all_actions( 'wp_print_styles' );
    remove_all_actions( 'wp_print_head_scripts' );

    global $wp_styles;
    global $wp_scripts;

    $wp_styles = new WP_Styles();
    $wp_scripts = new WP_Scripts();

    if ( (bool) $settings['show_wp_toolbar'] ) {
      add_action( 'add_admin_bar_menus', array( $this, 'update_admin_bar' ) );

      if ( !class_exists('WP_Admin_Bar') ) {
        _wp_admin_bar_init();
      }

      add_action('wp_enqueue_scripts_clean', array( $this, 'adminBarEnqueue' ));
      $this->show_admin_bar = true;
    } else {
      add_filter( 'show_admin_bar', '__return_false' );
    }

    Cornerstone_Code_Editor::instance(false)->register();
    Cornerstone_Huebert::instance(false)->register();
    $this->enqueue_styles( $settings );
    $this->enqueue_scripts( $settings );
    nocache_headers();
    $this->view( 'app/boilerplate', true );
    exit;

  }

  public function register_font_styles() {

    $subsets = 'latin,latin-ext';

    //
    // translators: To add an additional subset specific to your language,
    // translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
    //

    $subset = _x( 'cs-no-subset', 'Translate to: (greek, cyrillic, vietnamese) to add an additional font subset.' );

    if ( 'cyrillic' === $subset ) {
      $subsets .= ',cyrillic,cyrillic-ext';
    } elseif ( 'greek' === $subset ) {
      $subsets .= ',greek,greek-ext';
    } elseif ( 'vietnamese' === $subset ) {
      $subsets .= ',vietnamese';
    }

    wp_register_style( 'cs-open-sans', "https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600&subset=$subsets" );
    wp_register_style( 'cs-lato', "https://fonts.googleapis.com/css?family=Lato:400,700&subset=$subsets" );
  }

  public function enqueue_styles( $settings ) {

    $this->register_font_styles();

    wp_register_style( 'cs-dashicons', '/wp-includes/css/dashicons.min.css' );
    wp_enqueue_style( 'cs-app-style', $this->plugin->css( 'cs', true ), array( 'cs-open-sans', 'cs-lato', 'cs-dashicons' ), $this->plugin->version() );
    wp_enqueue_style( 'cs-huebert-style' );
    wp_enqueue_style( 'cs-code-editor-style' );
  }

  public function register_app_scripts( $settings, $isPreview = false ) {

    wp_register_script( 'cs-app-vendor', $this->plugin->js( 'cs-vendor', true ), array( 'jquery' ), $this->plugin->version(), false );
    wp_register_script( 'cs-app', $this->plugin->js( 'cs', true ), array( 'cs-app-vendor' ), $this->plugin->version(), false );

    $icon_maps = wp_parse_args( array(
      'elements' => add_query_arg( array( 'v' => $this->plugin->version() ), $this->plugin->url('assets/dist-app/svg/elements.svg') ),
      'interface' => add_query_arg( array( 'v' => $this->plugin->version() ), $this->plugin->url('assets/dist-app/svg/interface.svg') ),
    ), apply_filters( 'cornerstone_icon_map', array() ) );

    $router = $this->plugin->component( 'Router' );

    $app_controller = $this->plugin->controller( 'App' );
    wp_localize_script( 'cs-app', 'csAppData', cs_booleanize( array(
      'rootURL'         => '/' . trim( $this->plugin->common()->get_app_slug(), '/\\' ) . '/',
      'validPermalinks' => $router->is_permalink_structure_valid(),
      'initialRoute'    => $this->initial_route,
      'ajaxUrl'         => $router->get_ajax_url(),
      'fallbackAjaxUrl' => $router->get_fallback_ajax_url(),
      'useLegacyAjax'   => $router->use_legacy_ajax(),
      'debug'           => ( $this->plugin->common()->isDebug() ),
      'isRTL'           => is_rtl(),
      'i18n'            => $this->plugin->i18n( 'app' ),
      '_cs_nonce'       => wp_create_nonce( 'cornerstone_nonce' ),
      'permissions'     => $app_controller->permissions(),
      'funMode'         => (bool) $settings['visual_enhancements'],
      'fontAwesome'     => $this->plugin->common()->getFontIcons(),
      'iconMaps'        => $icon_maps,
      'isPreview'       => $isPreview,
      'previewData'     => $this->plugin->component( 'Preview_Frame' )->data(),
      'editorMarkup'    => $this->get_wp_editor(),
      'font_data'       => $app_controller->font_data(),
      'font_weights'    => $app_controller->font_weights(),
      'keybindings'     => apply_filters('cornerstone_keybindings', $this->plugin->config( 'builder/keybindings' ) ),
      'home_url'        => home_url()
    ) ) );

  }

  public function enqueue_scripts( $settings ) {

    $this->register_app_scripts( $settings );
    wp_enqueue_script( 'cs-app' );

    // Dependencies
    wp_enqueue_script( 'backbone' );
    wp_enqueue_script( 'cs-huebert' );
    wp_enqueue_script( 'cs-code-editor' );
    wp_enqueue_media();


  }

  public function update_admin_bar() {
    remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40 );
  }

  public function head() {
    wp_enqueue_scripts();
    wp_print_styles();
    wp_print_head_scripts();
  }

  public function footer() {

    wp_print_footer_scripts();
    wp_admin_bar_render();

    if ( function_exists( 'wp_underscore_playlist_templates' ) && function_exists( 'wp_print_media_templates' ) ) {
      wp_underscore_playlist_templates();
      wp_print_media_templates();
    }

  }

  public function body_classes() {

    $classes = array( 'no-customize-support' );

    if ( is_rtl() ) {
      $classes[] = 'rtl';
    }

    if ( $this->show_admin_bar ) {
      $classes[] = 'admin-bar';
    }

    if ( empty( $classes ) ) {
      return;
    }

    $classes = array_map( 'esc_attr', array_unique( $classes ) );
    $class = join( ' ', $classes );
    echo " class=\"$class\"";

  }

  public function title() {
    echo $this->plugin->common()->properTitle();
  }

  /**
   * Prepare the WordPress Editor (wp_editor) for use as a control
   * This thing does NOT like to be used in multiple contexts where it's added and removed dynamically.
   * We're creating some initial settings here to be used later.
   * Callings this function also triggers all the required styles/scripts to be enqueued.
   * @return none
   */
  public function primeEditor() {

    // Remove all 3rd party integrations to prevent plugin conflicts.
    remove_all_actions('before_wp_tiny_mce');
    remove_all_filters('mce_external_plugins');
    remove_all_filters('mce_buttons');
    remove_all_filters('tiny_mce_before_init');
    add_filter( 'tiny_mce_before_init', '_mce_set_direction' );

    // Cornerstone's editor is modified, so we will allow visual editing for all users.
    add_filter( 'user_can_richedit', '__return_true' );

    if( apply_filters( 'cornerstone_use_br_tags', false ) ) {
      add_filter('tiny_mce_before_init', array( $this, 'allow_br_tags' ) );
    }

    // Allow integrations to use hooks above before the editor is primed.
    do_action('cornerstone_before_wp_editor');

    ob_start();
    wp_editor( '%%PLACEHOLDER%%','cswpeditor', array(
      //'quicktags' => false,
      'tinymce'=> array(
        'toolbar1' => 'bold,italic,strikethrough,underline,bullist,numlist,forecolor,wp_adv',
        'toolbar2' => 'link,unlink,alignleft,aligncenter,alignright,alignjustify,outdent,indent',
        'toolbar3' => 'formatselect,pastetext,removeformat,charmap,undo,redo'
      ),
      'editor_class' => 'cs-wp-editor',
      'drag_drop_upload' => true
    ) );
    $this->cachedWPeditor = ob_get_clean();
  }

  /**
   * Get the WP Editor markup if it's been primed
   * @return string
   */
  public function get_wp_editor() {
    return isset( $this->cachedWPeditor ) ? $this->cachedWPeditor : '';
  }

  /**
   * Depending on workflow, users may wish to allow <br> tags.
   * This can be conditionally enabled with a filter.
   * add_filter( 'cornerstone_use_br_tags', '__return_true' );
   */
  public function allow_br_tags( $init ) {
    $init['forced_root_block'] = false;
    return $init;
  }

  /**
   * Is this the iFrame?
   * Check if the ?cornerstone_preview=1 query string has been added to the URL
   * @return boolean [description]
   */
  public function is_preview() {
    return ( isset($_GET['cs_app_preview']) && $_GET['cs_app_preview'] == 1 );
  }

}
