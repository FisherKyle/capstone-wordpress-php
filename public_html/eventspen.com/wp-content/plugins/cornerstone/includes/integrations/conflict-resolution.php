<?php

class Cornerstone_Integration_Conflict_Resolution {

  public function __construct() {

    add_action( 'cornerstone_load_builder', array( $this, 'front_end' ) );
    add_action( 'cornerstone_boot_app', array( $this, 'front_end' ) );
    add_action( 'cornerstone_before_custom_endpoint', array( $this, 'disable_caching' ) );
    add_action( 'cornerstone_before_ajax', array( $this, 'before_render' ) );
    add_action( 'cornerstone_before_load_preview', array( $this, 'before_load_preview' ) );

    //WPML Integration - pre_get_posts only works on __construct
    if ( class_exists( 'SitePress' ) && apply_filters( 'cornerstone_enable_wpml_integration', true ) ) {
      add_filter( 'pre_get_posts', array( $this, 'wpml_get_posts' ) );
      add_filter( 'the_title', array( $this, 'wpml_title' ), 99, 2 );
      add_filter( 'the_permalink', array( $this, 'wpml_permalink' ) );
      //Due to deep connection with cornerstone to the_excerpt, let's not add another excerpt filter for now
      //add_filter( 'get_the_excerpt', array( $this, 'wpml_excerpt') );
    }

  }

  public static function pre_init() {

    // Disable NextGEN Resource Manager
    add_filter( 'run_ngg_resource_manager', '__return_false' );

    global $wp_version;

    if ( version_compare( $wp_version, '4.2', '<' ) ) {
      require_once( CS()->path( 'includes/utility/wp.php' ) );
    }

  }

  public function front_end() {

    $this->disable_caching();

    if ( class_exists( 'UberMenu' ) ) {
      remove_action( 'admin_bar_menu', 'ubermenu_add_toolbar_items', 100 );
      //$this->remove_ubermenu_toolbar();
    }

    if ( class_exists( 'WPSEO_Frontend' ) ) {
      remove_action( 'template_redirect', array( WPSEO_Frontend::get_instance(), 'clean_permalink' ), 1 );
    }

  }

  public function disable_caching() {

    // Define constants shared throughout many caching and performance plugins.

    if ( ! defined( 'DONOTCACHEPAGE' ) ) {
      define( 'DONOTCACHEPAGE', true );
    }

    if ( ! defined( 'DONOTMINIFY' ) ) {
      define( 'DONOTMINIFY', true );
    }

    if ( ! defined( 'DONOTCDN' ) ) {
      define( 'DONOTCDN', true );
    }

    // Outliers who don't use the constants
    add_filter( 'bwp_minify_is_loadable', '__return_false' );

    // Optionally Disable CloudFlare Rocket Loader. This should in most cases
    // already be avoided by the no-cache headers.
    if ( apply_filters( 'cornerstone_compat_cloudflare', false ) ) {
      add_filter( 'script_loader_tag', array( $this, 'disable_cloudflare_rocket_loader' ), 10, 3 );
    }
  }

  public function before_render() {

    if ( class_exists( 'GFForms' ) ) {
      add_filter( 'gform_disable_print_form_scripts', '__return_true' );
    }

  }

  public function before_load_preview() {

    $this->front_end();

    if ( defined( 'JETPACK__VERSION' ) ) {
      remove_filter( 'the_content', 'sharing_display', 19 );
      remove_filter( 'the_excerpt', 'sharing_display', 19 );
      add_filter( 'sharing_show', '__return_false', 9999 );
    }

    if ( function_exists( 'wpseo_frontend_head_init' ) ) {
      remove_action( 'template_redirect', 'wpseo_frontend_head_init', 999 );
    }

    if ( function_exists( 'csshero_add_footer_trigger' ) ) {
      add_filter( 'pre_option_wpcss_hidetrigger', '__return_true' );
    }

  }

  public function disable_cloudflare_rocket_loader( $tag, $handle, $src ) {
    return str_replace( "type='text/javascript'", "type='text/javascript' data-cfasync=\"false\"", $tag );
  }

  public function wpml_get_posts ( $query ) {

    global $sitepress;

    if ( ! is_callable( array( $sitepress, 'switch_lang' ) ) || ! is_callable( array( $sitepress, 'get_current_language' ) ) ) {
      return;
    }

    $sitepress->switch_lang( $sitepress->get_current_language() ); //Make sure that even custom query gets the current language

    $query->query_vars['suppress_filters'] = false;

    return $query;

  }

  //WPML Post object usable by multiple filters
  private function wpml_post() {

    global $post, $sitepress;

    if ( ! $post || ! function_exists( 'icl_object_id' ) || ! is_callable( array( $sitepress, 'get_current_language' ) ) ) {
      return;
    }

    return get_post( icl_object_id( $post->ID, 'post', false, $sitepress->get_current_language() ) );
  }

  public function wpml_title( $title, $id ) {

    $wpml_post = $this->wpml_post();

    return ( ! is_a( $wpml_post, 'WP_Post' ) || $wpml_post->ID !== $id ) ? $title :
      // Let's apply the_title filters (apply_filters causes loop)
      trim( convert_chars( wptexturize( esc_html( $wpml_post->post_title ) ) ) );
  }

  public function wpml_permalink ( $permalink ) {

    $wpml_post = $this->wpml_post();

    if ( is_a( $wpml_post, 'WP_Post' ) ) {
      $permalink = get_permalink( $wpml_post->ID );
    }

    return $permalink;

  }

}
