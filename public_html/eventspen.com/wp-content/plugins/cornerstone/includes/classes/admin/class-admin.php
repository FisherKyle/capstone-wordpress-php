<?php
/**
 * This class manages all Dashboard related activity.
 * It handles the Options page, and adds the "Edit with Cornerstone"
 * links to the list table screens, and the toolbar.
 */

class Cornerstone_Admin extends Cornerstone_Plugin_Component {

	/**
	 * Cache settings locally
	 * @var array
	 */
	public $settings;

	/**
	 * Shortcut to our folder
	 * @var string
	 */
	public $path = 'includes/admin/';

	/**
	 * Store script data potentially used by multiple modules
	 * @var array
	 */
	public $script_data = array();

	/**
	 * Initialize, and add hooks
	 */
	public function setup() {

		add_action( 'admin_bar_menu', array( $this, 'addToolbarEditLink' ), 999 );

		if ( ! is_admin() ) {
			return;
		}

		Cornerstone_Huebert::init();
		Cornerstone_Code_Editor::init();

		add_action( 'admin_menu',            array( $this, 'optionsPage' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'page_row_actions',      array( $this, 'addRowActions' ), 10, 2 );
		add_filter( 'post_row_actions',      array( $this, 'addRowActions' ), 10, 2 );
		add_action( 'admin_notices',         array( $this, 'notices' ), 20 );

	}

	public function ajax_override() {

		if ( isset( $_POST['post_id'] ) && current_user_can( $this->plugin->common()->get_post_capability( $_POST['post_id'], 'edit_post' ), $_POST['post_id'] ) ) {
			update_post_meta( $_POST['post_id'], '_cornerstone_override', true );
		}

		return cs_send_json_success();

	}

	public function ajax_dismiss_validation_notice() {
		update_option( 'cornerstone_dismiss_validation_notice', true );
		return cs_send_json_success();
	}

	public function add_script_data( $handle, $callback ) {
		$this->script_data[$handle] = $callback;
	}

	public function get_script_data() {

		$modules = array();

		foreach ($this->script_data as $handle => $callback ) {
			if ( is_callable( $callback ) ) {
				$modules[$handle] = call_user_func( $callback );
			}
		}

		$notices = array();
		if ( isset( $_REQUEST['notice'] ) ) {
      $notices = explode( '|', sanitize_text_field( $_REQUEST['notice'] ) );
    }

		return array(
			'modules' => $modules,
			'notices' => $notices
		);

	}

	/**
	 * Enqueue Admin Scripts and Styles
	 */
	public function enqueue( $hook ) {

		wp_enqueue_style( 'cornerstone-admin-css', $this->plugin->css( 'admin/dashboard' ), array( cs_tco()->handle( 'admin-css' ) ), $this->plugin->version() );

		$post = $this->plugin->common()->locatePost();
		$post_id = ( $post ) ? $post->ID : 'new';
		$commonData = array(
			'homeURL'   => home_url(),
			'editURL'   => $this->plugin->common()->getEditURL(),
			'post_id'   => $post_id,
			'_cs_nonce' => wp_create_nonce( 'cornerstone_nonce' ),
			'strings'   => $this->plugin->i18n( 'admin', false ),
		);

		if ( false !== strpos( $hook, 'cornerstone-home' ) ) {

			wp_register_script( 'cs-dashboard-home-js', $this->plugin->js( 'admin/dashboard-home' ) , array( cs_tco()->handle( 'admin-js' )  ), $this->plugin->version(), true );
			wp_localize_script( 'cs-dashboard-home-js', 'csDashboardHomeData', array_merge( $commonData, $this->get_script_data() ) );
			wp_enqueue_script( 'cs-dashboard-home-js' );

		}

		if ( false !== strpos( $hook, 'cornerstone-settings' ) ) {

			wp_register_script( 'cs-dashboard-setting-js', $this->plugin->js( 'admin/dashboard-settings' ) , array( cs_tco()->handle( 'admin-js' )  ), $this->plugin->version(), true );
			wp_localize_script( 'cs-dashboard-setting-js', 'csDashboardSettingsData', array_merge( $commonData, array(
				'modules' => array(
					'cs-settings' => array(
						'update'   => __( 'Update', 'cornerstone' ),
						'updating' => __( 'Updating&hellip;', 'cornerstone' ),
						'updated'  => __( 'Settings Saved!', 'cornerstone' ),
						'error'    => __( 'Sorry! Unable to Save', 'cornerstone' ),
					)
				)
			) ) );
			wp_enqueue_script( 'cs-dashboard-setting-js' );

		}

		if ( $this->isPostEditor( $hook ) ) {

			wp_register_script( 'cs-dashboard-post-editor-js', $this->plugin->js( 'admin/dashboard-post-editor' ) , array( cs_tco()->handle( 'admin-js' )  ), $this->plugin->version(), true );

			wp_localize_script( 'cs-dashboard-post-editor-js', 'csDashboardPostEditorData', array_merge( $commonData, array(
				'usesCornerstone' => ( $this->plugin->common()->uses_cornerstone() ) ? 'true' : 'false',
				'editorTabMarkup' => $this->view( 'admin/editor-tab', false ),
			) ) );

			wp_enqueue_script( 'cs-dashboard-post-editor-js' );

		}

	}


	/**
	 * Determine if the post editor is being viewed, and Cornerstone is available
	 * @param  string  $hook passed through from admin_enqueue_scripts hook
	 * @return boolean
	 */
	public function isPostEditor( $hook ) {

		if ( 'post.php' === $hook && isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) {
			return $this->plugin->common()->isPostTypeAllowed();
		}

		if ( 'post-new.php' === $hook && isset( $_GET['post_type'] ) ) {
			return in_array( $_GET['post_type'], $this->plugin->common()->getAllowedPostTypes(), true );
		}

		if ( 'post-new.php' === $hook && ! isset( $_GET['post_type'] ) ) {
			return in_array( 'post', $this->plugin->common()->getAllowedPostTypes(), true );
		}

		return false;
	}

	/**
	 * Register the Options page
	 */
	public function optionsPage() {
		$title = $this->plugin->common()->properTitle();

		add_menu_page( $title, $title, 'manage_options', 'cornerstone-home', array( $this, 'render_home_page' ), $this->make_menu_icon() );
		add_submenu_page( 'cornerstone-home', $title, __( 'Home', 'cornerstone' ), 'manage_options', 'cornerstone-home', array( $this, 'render_home_page' ) );
		add_submenu_page( 'cornerstone-home', $title, __( 'Settings', 'cornerstone' ), 'manage_options', 'cornerstone-settings', array( $this, 'render_settings_page' ) );


	}

	public function render_home_page() {

		if ( ! has_action( '_cornerstone_home_not_validated' ) ) {
			add_action( '_cornerstone_home_not_validated', array( $this, 'render_not_validated' ) );
		}

		do_action( '_cornerstone_home_before' );

		$is_validated             = $this->plugin->common()->is_validated();
		$status_icon_dynamic      = ( $is_validated ) ? '<div class="tco-box-status tco-box-status-validated">' . cs_tco()->get_admin_icon( 'unlocked' ) . '</div>' : '<div class="tco-box-status tco-box-status-unvalidated">' . cs_tco()->get_admin_icon( 'locked' ) . '</div>';

		include( $this->locate_view( 'admin/home' ) );

		do_action( '_cornerstone_home_after' );

	}

	public function render_not_validated() {
		$this->view( 'admin/home-validation' );
	}

	/**
	 * Callback to render the settings page.
	 */
	public function render_settings_page() {

		$this->settings_handler = $this->plugin->loadComponent( 'Settings_Handler' );
		$this->settings_handler->setup_controls();
		include( $this->plugin->locate_view( 'admin/settings' ) );

	}

	/**
	 * Add "Edit With Cornerstone" links to the WP List tables
	 * Filter applied to page_row_actions and post_row_actions
	 * @param array $actions
	 * @param object $post
	 */
	public function addRowActions( $actions, $post ) {

		if ( $this->plugin->common()->isPostTypeAllowed( $post ) ) {
			$url = $this->plugin->common()->getEditURL( $post );
			$label = __( 'Edit with Cornerstone', 'cornerstone' );
			$actions['edit_cornerstone'] = "<a href=\"$url\">$label</a>";
		}

		return $actions;
	}


	/**
	 * Add "Edit with Cornerstone" button on the toolbar
	 * This is only added on singlular views, and if the post type is supported
	 */
	public function addToolbarEditLink() {

		if ( is_singular() && $this->plugin->common()->isPostTypeAllowed() && $this->plugin->common()->uses_cornerstone() ) {

			global $wp_admin_bar;

			$wp_admin_bar->add_menu( array(
				'id' => 'cornerstone-edit-link',
				'title' => __( 'Edit with Cornerstone', 'cornerstone' ),
				'href' => $this->plugin->common()->getEditURL(),
				'meta' => array( 'class' => 'cornerstone-edit-link' ),
			) );

		}

	}

	/**
	 * Load View files
	 */

	public function notices() {

		$show_cornerstone_validation_notice = ( false === get_option( 'cornerstone_dismiss_validation_notice', false ) && ! $this->plugin->common()->is_validated() && ! in_array( get_current_screen()->parent_base, apply_filters( 'cornerstone_validation_notice_blocked_screens', array( 'cornerstone-home' ) ) ) );

		if ( $show_cornerstone_validation_notice && ! apply_filters( '_cornerstone_integration_remove_global_validation_notice', false ) ) {

			cs_tco()->admin_notice( array(
				'message' => sprintf( __( 'This Cornerstone license is ​<strong>not validated</strong>​. <a href="%s">Fix</a>.', 'cornerstone' ), $this->home_page_url() ),
				'dismissible' => true,
				'ajax_dismiss' => 'cs_dismiss_validation_notice'
			) );

		}

	}

	public function make_menu_icon() {
		return 'data:image/svg+xml;utf8,' . str_replace('"', "'", $this->view( 'svg/logo-dashboard-icon', false ) );
	}

	public function home_page_url() {
		return admin_url( 'admin.php?page=cornerstone-home' );
	}

}
