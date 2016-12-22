<?php

class Cornerstone_Validation extends Cornerstone_Plugin_Component {

	public function setup() {

		if ( ! is_admin() ) return;

		add_action( 'admin_enqueue_scripts', array( $this, 'add_script_data' ), -100 );

	}

	public function add_script_data() {
		$this->plugin->component( 'Admin' )->add_script_data( 'cs-validation', array( $this, 'script_data' ) );
		$this->plugin->component( 'Admin' )->add_script_data( 'cs-validation-revoke', array( $this, 'script_data_revoke' ) );
	}

	public function script_data() {
		return array(
			'verifying'   => __( 'Verifying license&hellip;', 'cornerstone' ),
			'error'       => __( '<strong>Uh oh</strong>, we couldn&apos;t check if this license was valid. <a data-tco-error-details href="#">Details.</a>', 'cornerstone' ),
			'notices'     => array(
				'validation-complete' => __( '<strong>Congratulations!</strong> Cornerstone is now validated for this site!', '__x__ ' )
			),
			'errorButton' => __( 'Go Back', 'cornerstone' ),
		);
	}

	public function script_data_revoke() {
		return array(
			'confirm'  => __( 'By revoking validation, you will no longer receive automatic updates. The site will still be linked in your Themeco account, so you can re-validate at anytime.<br/><br/> Visit "Licenses" in your Themeco account to transfer a license to another site.', 'cornerstone' ),
			'accept'   => __( 'Yes, revoke validation', 'cornerstone' ),
			'decline'  => __( 'Stay validated', 'cornerstone' ),
			'revoking' => __( 'Revoking&hellip;', 'cornerstone' ),
			'notices'  => array(
				'validation-revoked' => sprintf( __( '<strong>Validation revoked.</strong> You can re-assign licenses from <a href="%s" target="_blank">Manage Licenses</a>.', 'cornerstone' ), 'https://community.theme.co/my-licenses/' )
			)
		);
	}

	public function ajax_validation() {

		if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['code'] ) || ! $_POST['code'] ) {
			wp_send_json_error( array( 'message' => 'No purchase code specified.' ) );
		}

		$this->code = sanitize_text_field( $_POST['code'] );
		$validator = new TCO_Validator( $this->code, 'cornerstone' );

		$validator->run();

		if ( $validator->has_connection_error() ) {
			wp_send_json_error( array( 'message' => $validator->connection_error_details() ) );
		}

		$response = $this->get_validation_response( $validator );

		if ( isset( $response['complete'] ) && $response['complete'] ) {
			$this->update_validation( $this->code );
		} else {
			$this->update_validation( false );
		}

		wp_send_json_success( $response );

	}

	public function get_validation_response( $validator ) {

		// Purchase code is not valid
		if ( ! $validator->is_valid() ) {
			return array(
				'message' => __( 'We&apos;ve checked the code, but it <strong>doesn&apos;t appear to be an Cornerstone purchase code or Themeco license.</strong> Please double check the code and try again.', 'cornerstone' ),
				'button'  => __( 'Go Back', 'cornerstone' ),
				'dismiss' => true,
			);
		}

		// Valid, but the purchase code isn't associated with an account.
		if ( ! $validator->is_verified() ) {
      return array(
        'message' => __( 'This looks like a <strong>brand new purchase code that hasn&apos;t been added to a Themeco account yet.</strong> Login to your existing account or register a new one to continue.', 'cornerstone' ),
        'button'  => __( 'Login or Register', 'cornerstone' ),
        'url'     => add_query_arg( $this->out_params(), 'https://community.theme.co/product-validation/' )
      );
    }

    // Purchase code linked to an account, but doesn't have a site
    if ( ! $validator->has_site() ) {
      return array(
        'message' => __( 'Your code is valid, but <strong>we couldn&apos;t automatically link it to your site.</strong> You can add this site from within your Themeco account.', 'cornerstone' ),
        'button'  => __( 'Manage Licenses', 'cornerstone' ),
        'url'     => 'https://community.theme.co/my-licenses/',
        'dismiss' => true,
        'newTab'  => true
      );
    }

    // Purchase code linked, and site exists, but doesn't match this site.
    if ( ! $validator->site_match() ) {
      return array(
        'message' => __( 'Your code is valid but looks like it has <strong>already been used on another site.</strong> You can revoke and re-assign within your Themeco account.', 'cornerstone' ),
        'button'  => __( 'Manage Licenses', 'cornerstone' ),
        'url'     => 'https://community.theme.co/my-licenses/',
        'dismiss' => true,
        'newTab'  => true
      );
    }

    return array(
      'complete' => true,
      'message' => __( '<strong>Congratulations,</strong> your site is now validated!', 'cornerstone' )
    );

  }

  public function out_params() {
    return array(
      'code'        => $this->code,
      'product'     => 'cornerstone',
      'siteurl'     => cs_tco()->get_site_url(),
      'return-url'  => esc_url( $this->plugin->component( 'Admin' )->home_page_url() )
    );
  }

  public function ajax_revoke() {

  	if ( ! current_user_can( 'manage_options' ) ) {
  		wp_send_json_error();
  	}

    $this->update_validation( false );
    wp_send_json_success();

  }

  public function update_validation( $code ) {

    if ( $code ) {
      update_option( 'cs_product_validation_key', $code );
    } else {
      delete_option( 'cs_product_validation_key' );
    }

    cs_tco()->updates()->refresh();

  }

  public function preload_key() {
    $key = '';
    if ( isset( $_REQUEST['tco-key'] ) ) {
      $key = esc_html( $_REQUEST['tco-key'] );
    }
    return $key;
  }

}
